<?php

namespace App\Repositories;

use App\Contracts\OrderContract;
use App\Http\Bitrix\Deal\BitrixDeal;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\Service;
use App\Models\ShippingLocation;
use App\Models\ShippingType;

class OrderRepository implements OrderContract
{
    protected Order $model;

    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    /**
     * @param array $orderData
     * @param array $orderItems
     * @return Order
     */
    public function storeOrder(array $orderData, array $orderItems): Order
    {
        // total price with services and attributes
        $total = $this->getTotalProductsPrice($orderItems);
        $shippingTypeId = isset($orderData['shipping_type_id']) ? $orderData['shipping_type_id'] : null;
        $shippingLocationId = isset($orderData['shipping_location_id']) ? $orderData['shipping_location_id'] : null;
        $bitrixProducts = $this->formatProductsToBitrix($orderItems, $shippingLocationId, $shippingTypeId);

        $total += $this->getDeliveryPrice($shippingTypeId, $shippingLocationId);
        if (!isset($orderData['id'])) {
            try {
                $bitrixOrder = [
                    'TITLE' => 'Заказ с сайта',
                    'CONTACT_ID' => $orderData['user_id'],
                    'OPPORTUNITY' => $total,
                    'SOURCE_ID' => 'WEB',
                    'COMMENTS' => $orderData['comment'] ?? '',
                    'UF_CRM_1692881840251' => $orderData['shipping_address'] ?? '',
                ];
                $deal = new BitrixDeal();
                // TODO add products to deal
                $deal_id = $deal->createDeal($bitrixOrder, $bitrixProducts)->toArray()['result'];
                $orderData['id'] = $deal_id;
            } catch (\Exception $e) {
                dd($e);
                // dd($e->getMessage());
            }
     
        }
        if (empty($orderData['total'])) {
            $orderData['total'] = $total;
            // TODO total price with discount
            $orderData['sub_total'] = $total;
        }
        $order = $this->model->create($orderData);

        $this->createOrderProducts($order, $orderItems);

        return $order->load('orderItems');
    }

    private function getDeliveryPrice($shippingTypeId, $shippingLocationId)
    {
        $deliveryPrice = 0;
        if (isset($shippingTypeId)) {
            $shippingType = ShippingType::query()->find($shippingTypeId);
            if (!empty($shippingType)) {
                if ($shippingType->price) {
                    $deliveryPrice += $shippingType->price;
                }
            }
        }
        if (isset($shippingLocationId)) {
            $shippingLocation = ShippingLocation::query()->find($shippingLocationId);
            if (!empty($shippingLocation)) {
                if ($shippingLocation->service->price) {
                    $deliveryPrice += $shippingLocation->service->price;
                }
            }
        }
        return $deliveryPrice;
    }

    public function createOrderProducts(Order $order, array $orderItems)
    {
        foreach ($orderItems as $orderItem) {
            $created_order_item = $this->createOrderProduct($order, $orderItem['PRODUCT_ID'] ?? $orderItem['product_id'], $orderItem['QUANTITY'] ?? $orderItem['quantity']);

            if ($created_order_item) {
                $this->createOrderItemAttributes($created_order_item, $orderItem['product_attributes'] ?? []);

                /** for site */
                $this->createOrderServices($order, $orderItem['services'] ?? []);
            }
        }
    }

    private function createOrderProduct(Order $order, int $product_id, int $quantity)
    {
        $item = Product::query()->find($product_id);

        if (!$item) {
            $item = Service::query()->find($product_id);
            if ($item) {
                /** проверка что является ли это услуга доставки(локация) */
                foreach (ShippingLocation::all() as $shippingLocation) {
                    if ($shippingLocation->service) {
                        if ($shippingLocation->service->id === $product_id) {
                            $order->update([
                                'shipping_location_id' => $shippingLocation->id,
                            ]);
                            break;
                        }
                    }
                }
                /** проверка что является ли это услуга доставки(тип) */
                foreach (ShippingType::all() as $shippingType) {
                    if ($shippingType->service) {
                        if ($shippingType->service->id === $product_id) {
                            $order->update([
                                'shipping_type_id' => $shippingType->id,
                            ]);
                            break;
                        }
                    }
                }
                return $order->orderServices()->create([
                    'order_id' => $order->id,
                    'service_id' => $item->id,
                    'service_name' => $item->name,
                    'service_price' => $item->price,
                    'service_unit' => $item->unit,
                    'service_quantity' => $quantity,
                    'service_sku' => $item->sku,
                ]);
            }
        }

        return $order->orderItems()->create([
            'order_id' => $order->id,
            'product_id' => $item->id,
            'product_name' => $item->name,
            'product_sku' => $item->sku ?? '123',
            'product_base_price' => $item->base_price,
            'product_unit' => $item->unit,
            'quantity' => $quantity,
            'provider_name' => $item->provider->name,
            'collection_name' => $item->collection ? $item->collection->name : null,
            'category_name' => $item->category ? $item->category->name : null,
            'subcategory_name' => $item->subcategory ? $item->subcategory->name : null,
            'product_discount' => $item->discount,
            'product_image' => $item->image,
        ]);
    }

    private function createOrderItemAttributes($createdOrderItem, $orderItemAttributes)
    {
        foreach ($orderItemAttributes as $orderItemAttribute) {
            $product_attribute = ProductAttribute::query()->find($orderItemAttribute['product_attribute_id']);

            $createdOrderItem->attributes()->create([
                'order_item_id' => $createdOrderItem->id,
                'product_attribute_id' => $product_attribute->id,
                'attribute_name' => $product_attribute->attribute->name,
                'attribute_value' => $product_attribute->value,
                'attribute_price' => $product_attribute->price,
                'attribute_unit' => $product_attribute->attribute->unit,
                'attribute_sku' => $product_attribute->sku,
            ]);
        }
    }

    private function createOrderServices($createdOrder, $orderItemServices)
    {
        foreach ($orderItemServices as $orderItemService) {
            $service = Service::query()->find($orderItemService['service_id']);

            $createdOrder->orderServices()->create([
                'order_id' => $createdOrder->id,
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_sku' => $service->sku,
                'service_price' => $service->price,
                'service_unit' => $service->unit,
                'service_value' => $orderItemService['service_value'],
                'service_quantity' => $orderItemService['service_quantity'],
            ]);
        }
    }

    /**
     * description: get total products price with services and attributes
     * @param $orderItems
     */
    private function getTotalProductsPrice($orderItems)
    {
        $total = 0;
        foreach ($orderItems as $orderItem) {
            $product = Product::query()->find($orderItem['product_id'] ?? $orderItem['PRODUCT_ID']);
            if ($product) {
                $attributes_price = $this->getProductAttributesPrice($orderItem['product_attributes'] ?? []);
                $services_price = $this->getProductServicesPrice($orderItem['services'] ?? [], ($orderItem['quantity'] ?? $orderItem['QUANTITY']));

                $total += (($product->base_price + $attributes_price) * ($orderItem['quantity'] ?? $orderItem['QUANTITY'])) + $services_price;
            } else {
                $service = Service::query()->find($orderItem['product_id'] ?? $orderItem['PRODUCT_ID']);
                if ($service) {
                    $total += (($service->price) * ($orderItem['quantity'] ?? $orderItem['QUANTITY']));
                }
            }
        }
        return $total;
    }

    private function getProductAttributesPrice($orderItemAttributes)
    {
        $attribute_price = 0;
        if (isset($orderItemAttributes)) {
            foreach ($orderItemAttributes as $orderItemAttribute) {
                $attribute = ProductAttribute::query()->find($orderItemAttribute['product_attribute_id']);
                $attribute_price += $attribute->price;
            }
        }
        return $attribute_price;
    }

    private function getProductServicesPrice($orderItemServices, $orderItemQuantity)
    {
        $services_price = 0;
        if (isset($orderItemServices)) {
            foreach ($orderItemServices as $orderItemService) {
                $service = Service::query()->find($orderItemService['service_id']);
                $service_quantity = $orderItemService['service_quantity'] ?? $orderItemQuantity;
                $service_value = $orderItemService['service_value'] ?? 1;
                $services_price += $service->price * $service_value * $service_quantity;
            }
        }
        return $services_price;
    }

    private function formatProductsToBitrix($orderProducts, $shippingLocationId, $shippingTypeId)
    {
        $bitrixProducts = [];

        foreach ($orderProducts as $orderProduct) {
            $product = Product::query()->find($orderProduct['PRODUCT_ID'] ?? $orderProduct['product_id']);
            if ($product) {
                $attribute_price = $this->getProductAttributesPrice($orderProduct['product_attributes'] ?? []);
                $bitrixProducts[] = [
                    'PRODUCT_ID' => $orderProduct['PRODUCT_ID'] ?? $orderProduct['product_id'],
                    'QUANTITY' => $orderProduct['QUANTITY'] ?? $orderProduct['quantity'],
                    'PRICE' => $product->base_price + $attribute_price,
                ];

                if (isset($orderProduct['services'])) {
                    foreach ($orderProduct['services'] as $serviceItem) {
                        $service = Service::query()->find($serviceItem['service_id']);
                        if ($service) {
                            $bitrixProducts[] = [
                                'PRODUCT_ID' => $serviceItem['service_id'],
                                'QUANTITY' => $orderProduct['service_quantity'] ?? 1,
                                'PRICE' => $service->price,
                            ];
                        }
                    }
                }
            } else {
                $service = Service::query()->find($orderProduct['PRODUCT_ID'] ?? $orderProduct['product_id']);
                if ($service) {
                    $bitrixProducts[] = [
                        'PRODUCT_ID' => $orderProduct['PRODUCT_ID'] ?? $orderProduct['product_id'],
                        'QUANTITY' => $orderProduct['QUANTITY'] ?? $orderProduct['quantity'],
                        'PRICE' => $service->price,
                    ];
                }
            }
        }
        if ($shippingLocationId) {
            $shippingLocation = ShippingLocation::query()->find($shippingLocationId);
            if ($shippingLocation) {
                if ($shippingLocation->service) {
                    $bitrixProducts[] = [
                        'PRODUCT_ID' => $shippingLocation->service->id,
                        'QUANTITY' => 1,
                        'PRICE' => $shippingLocation->service->price,
                    ];
                }
            }
        }
        if ($shippingTypeId) {
            $shippingType = ShippingType::query()->find($shippingTypeId);
            if ($shippingType) {
                if ($shippingType->service) {
                    $bitrixProducts[] = [
                        'PRODUCT_ID' => $shippingType->service->id,
                        'QUANTITY' => 1,
                        'PRICE' => $shippingType->service->price,
                    ];
                }
            }
        }
        return $bitrixProducts;
    }

    /**
     * @param $order
     * @param $statusId
     * @return Order
     */
    public function updateOrder($order, $statusId): Order
    {
        $order->status_id = $statusId;
        $order->save();
        return $order;
    }
}
