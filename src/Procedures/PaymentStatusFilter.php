<?php
/**
 * This module is used for real time processing of
 * Novalnet payment module of customers.
 * This free contribution made by request.
 * 
 * If you have found this script useful a small
 * recommendation as well as a comment on merchant form
 * would be greatly appreciated.
 *
 * @author       Novalnet AG
 * @copyright(C) Novalnet
 * All rights reserved. https://www.novalnet.de/payment-plugins/kostenlos/lizenz
 */
 
namespace Novalnet\Procedures;

use Plenty\Modules\EventProcedures\Events\EventProceduresTriggered;
use Plenty\Modules\Order\Models\Order;
use Plenty\Plugin\Log\Loggable;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;

/**
 * Class PaymentStatusFilter
 */
class PaymentStatusFilter
{
    use Loggable;
 
    /**
     * Filter process for the pending and on-hold payment status 
     *
     * @param EventProceduresTriggered $eventTriggered
     * 
     */
    public function awaiting(EventProceduresTriggered $eventTriggered) {
        $this->getNovalnetOrderPaymentStatus($eventTriggered);
    }
    
     /**
     * Filter process for the confirmed payment status 
     *
     * @param EventProceduresTriggered $eventTriggered
     * 
     */
    public function confirmed(EventProceduresTriggered $eventTriggered) {
       $this->getNovalnetOrderPaymentStatus($eventTriggered);
    }
   
   /**
     * Filter process for the confirmed payment status 
     *
     * @param EventProceduresTriggered $eventTriggered
     * 
     */
   public function canceled(EventProceduresTriggered $eventTriggered) {
      $this->getNovalnetOrderPaymentStatus($eventTriggered);
   }
  
   /**
     * Get the payment status based on the order Id
     *
     * return bool 
    */
   public function getNovalnetOrderPaymentStatus($eventTriggered) {
       /* @var $order Order */
       $order = $eventTriggered->getOrder(); 
       $payments = pluginApp(\Plenty\Modules\Payment\Contracts\PaymentRepositoryContract::class);  
       $paymentDetails = $payments->getPaymentsByOrderId($order->id);
       $this->getLogger(__METHOD__)->error('test', $paymentDetails);
       foreach ($paymentDetails as $paymentDetail) {
          $paymentStatus = $paymentDetail->status;
       }
       
       if(in_array($paymentStatus, [1, 3, 5])) {
          $this->getLogger(__METHOD__)->error('status123', $paymentStatus);
          return true;
       } else {
        $this->getLogger(__METHOD__)->error('status12345', $paymentStatus);
          return false;
       }
   }
}
