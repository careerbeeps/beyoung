<?php
namespace Pickrr\Shipment\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Event\ObserverInterface;
class Create implements \Magento\Framework\Event\ObserverInterface
{

  private $orderFactory;

  public function __construct(\Magento\Sales\Model\OrderFactory $orderFactory) {
        $this->orderFactory   = $orderFactory;
  }

  public function execute(\Magento\Framework\Event\Observer $observer)
  {
  	try{
  		$lastOrderId = $observer->getEvent()->getData('order_ids');
  		$order = $this->orderFactory->create()->load($lastOrderId[0]);
  		$shipping_address = $order->getShippingAddress(); 

  	
	    $payment = $order->getPayment();
            $method = $payment->getMethod();
	    if(strpos($method, "cashondelivery") !== false)
	        $cod_amount = $order->getGrandTotal();
	    else
	        $cod_amount = 0.0;
	    $invoice_amount = $order->getGrandTotal();


	    $auth_token = "6c96b95bb99c767660312f5fd97c558732735";

	    $from_name = "Beyoung Folks Pvt Ltd";
	    $from_phone_number = "8696633366";
	    $from_pincode = "313002";
	    $from_address = "1-C 12, Gayatri Nagar, Hiran Magari sector 5, Udaipur";
	    
	    $shipping_address = $order->getShippingAddress();            
	    $to_name = $shipping_address->getName();
	    $to_phone_number = $shipping_address->getTelephone();
	    $to_pincode = $shipping_address->getPostcode();
	    $to_address = implode(', ', $shipping_address->getStreet()) . ", " . $shipping_address->getCity() . ", " . $shipping_address->getRegion();
	    $order_id = $order->getIncrementId();
	    $itemCount = $order->getTotalItemCount();
	    $item_name = "NULL";

    	if($itemCount==1) $item_name = $order->getItemsCollection()->getFirstItem()->getName();
    	else $item_name = 'Multiple Items';

	    $params = array(
	                  'auth_token' => $auth_token,
	                  'item_name' => $item_name,
	                  'from_name' => $from_name,
	                  'from_phone_number' => $from_phone_number,
	                  'from_pincode'=> $from_pincode,
	                  'from_address'=> $from_address,
	                  'to_name'=> $to_name,
	                  'to_phone_number' => $to_phone_number,
	                  'to_pincode' => $to_pincode,
	                  'to_address' => $to_address,
	                  'client_order_id' => $order_id,
	                  'invoice_value' => $invoice_amount
	                );

        if($cod_amount>0.0) $params['cod_amount'] = $cod_amount;

        $json_params = json_encode( $params );

        $url = 'http://www.pickrr.com/api/place-order/';
        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $json_params);
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        //execute post
        $result = curl_exec($ch);
        $result = json_decode($result, true);

	        //close connection
        curl_close($ch);

        if(gettype($result)!="array")
          throw new \Exception( print_r($result, true) . "Problem in connecting with Pickrr");

        if($result['err']!="")
          throw new \Exception($result['err']);

        return $result['tracking_id'];

    }
    catch (\Exception $e) {
        throw new LocalizedException(__('There was an error in creating the Pickrr shipment: %1.', $e->getMessage()));
    }
  }
}
