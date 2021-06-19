<?php

namespace SourceBans\Payment;

abstract class AbstractPayment
{
    /**
     * Returns the name of this SourceBans Payment Service.
     */
    public function getName() {}

    /**
     * Returns the author name. Allowed HTML chars.
     */
    public function getAuthor() {}

    /**
     * Returns the version.
     */
    public function getVersion() {}

    /**
     * Returns the provider WebSite.
     */
    public function getUrl() {}

    /**
     * Generate client sign.
     */
    public function getClientSign() {}

    /**
     * Generate notification sign.
     */
    public function getNotifySign() {}

    /**
     * Generate URL for client redirect.
     */
    public function generatePaymentUrl() {}
}