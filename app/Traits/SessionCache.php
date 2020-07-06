<?php


namespace App\Traits;


use Illuminate\Support\Facades\Session;

trait SessionCache
{
    /**
     * Stores the basket in the user's shopping cart
     *
     * @param $basket
     *
     * @return void
     */
    public function cacheBasket($basket)
    {

        $this->session->put('basket', $basket);
    }

    /**
     * Returns the cached shopping cart
     *
     * @return Cart|null
     */
    public function getCachedBasket()
    {
//        dd($this->session->get('basket'));
        return $this->session->get('basket');
    }

    /**
     * Returns the products stored in session
     *
     * @return Collection|null
     */
    public function getCachedProducts()
    {
        return Session::get('unique_item_ids');
    }

    /**
     * Removes both the basket and the products in it, from the current session
     *
     * @return void
     */
    public function emptyCache()
    {

        if ($this->productsAreCached())  Session::pull('unique_item_ids');

    }
    /**
     * Checks is any products have been cached in the user's session
     *
     * @return boolean
     */
    public function productsAreCached()
    {
        return Session::has('unique_item_ids');
    }

    /**
     * Removes products cached in the session
     *
     * @return void
     */
    public function removeCachedProducts()
    {
        Session::pull('unique_item_ids');
    }
}
