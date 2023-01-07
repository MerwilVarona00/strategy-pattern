<?php

namespace Strategy;

use Strategy\Cart\Item;
use Strategy\Cart\ShoppingCart;
use Strategy\Order\Order;
use Strategy\Invoice\TextInvoice;
use Strategy\Invoice\PDFInvoice;
use Strategy\Customer\Customer;
use Strategy\Payments\CashOnDelivery;
use Strategy\Payments\CreditCardPayment;
use Strategy\Payments\PaypalPayment;

class Application
{
    public static function run()
    {
        $rolex = new Item('ROLEX', 'Rolex Submariner' , 246000);
        $omega = new Item('OMEGA', 'Omega Seamaster 300M', 50000);
        $carrera = new Item('CARRERA', 'TAG Heuer Carrera', 15000);

        $cart = new ShoppingCart();
        $cart->addItem($carrera, 1);
        $cart->addItem($rolex, 1);

        $customer = new Customer('Merwil Varona', 'Purok 1, Barangay Cuayan Angeles City', 'varona.merwil@auf.edu.ph');
        
        $order = new Order($customer, $cart);

        $text_invoice = new TextInvoice();
        $order->setInvoiceGenerator($text_invoice);
        $text_invoice->generate($order);

        $cash_on_delivery = new CashOnDelivery($customer);
        $order->setPaymentMethod($cash_on_delivery);
        $order->payInvoice();
        
        echo "\n -------------------------------------------------- \n\n";

        $cart2 = new ShoppingCart();
        $cart2->addItem($omega,1);
        $cart2->addItem($rolex,1);
        $cart2->addItem($carrera,1);

        $order2 = new Order($customer, $cart2);

        $pdf_invoice = new PDFInvoice();
        $order2->setInvoiceGenerator($pdf_invoice);
        $pdf_invoice->generate($order2);

        $paypal_payment = new PaypalPayment('varona.merwil@auf.edu.ph', '12345678');
        $order2->setPaymentMethod($paypal_payment);
        $order2->payInvoice();

        echo "\n -------------------------------------------------- \n\n";

        $customer2 = new Customer('Harvey Specter', '25 Cansas Rich Town San Fernando Pampanga', 'specter.harvey@auf.edu.ph');

        $cart3 = new ShoppingCart();
        $cart3->addItem($rolex, 1);
        $cart3->addItem($omega, 1);
        $cart3->addItem($carrera, 1);


        $order3 = new Order($customer2, $cart3);
        $order3->setInvoiceGenerator($text_invoice);
        $text_invoice->generate($order3);

        $credit_card_payment = new CreditCardPayment('Harvey Specter', '11111111', '0303', '05/25');
        $order3->setPaymentMethod($credit_card_payment);
        $order3->payInvoice();
    }
}