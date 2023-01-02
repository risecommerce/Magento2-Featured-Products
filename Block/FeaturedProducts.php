<?php

namespace Risecommerce\FeaturedProducts\Block;

use Magento\Framework\App\Action\Action;

/**
 * Class FeaturedProducts
 * @package Risecommerce\FeaturedProducts\Block
 */
class FeaturedProducts extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Catalog\Block\Product\ListProduct
     */
    protected $listProductBlock;

    /**
     * @var \Magento\Review\Model\ReviewFactory
     */
    protected $reviewFactory;

    /**
     * @var Widget Template file path
     */
    protected $_template = 'featuredproducts.phtml';

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $catalogProductVisibility;

    /**
     * Construct
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param  \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
     * @param  \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param  \Magento\Catalog\Block\Product\ListProduct $listProductBlock
     * @param  \Magento\Review\Model\ReviewFactory $reviewFactory
     * @param  \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Block\Product\ListProduct $listProductBlock,
        \Magento\Review\Model\ReviewFactory $reviewFactory,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->listProductBlock = $listProductBlock;
        $this->reviewFactory = $reviewFactory;
        $this->catalogProductVisibility = $catalogProductVisibility;
        parent::__construct($context, $data);
    }

    /**
     * @return FeaturedProducts items
     */
    public function getFeaturedProducts()
    {
        return $this->getProductCollection()->getItems();
    }

    /**
     * @return Catalog Products Collection
     */
    public function getProductCollection()
    {
        $collection = $this->productCollectionFactory->create();
        $collection->setVisibility($this->catalogProductVisibility->getVisibleInCatalogIds());
        $collection->addMinimalPrice()
                    ->addFinalPrice()
                    ->addTaxPercents()
                    ->addAttributeToSelect('*')
                    ->addAttributeToFilter('is_featuredproduct', '1');
        return $collection;
    }

    /**
     * Get product image
     *
     * @param $getProductImage
     * @return string
     */
    public function getProductImage($getProductImage)
    {
        $placeholderImageUrl = '';
        $imageHelper = \Magento\Framework\App\ObjectManager::getInstance()->get(\Magento\Catalog\Helper\Image::class);
        $placeholderImageUrl = $imageHelper->getDefaultPlaceholderUrl('image');
        if (empty($getProductImage) || $getProductImage == 'no_selection') {
            return $placeholderImageUrl;
        } else {
            return $this->getMediaBaseUrl() . $getProductImage;
        }
    }

    /**
     * Get media base URL
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaBaseUrl()
    {
        return $this->storeManager
                    ->getStore()
                    ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product';
    }

    /**
     * Get add to cart post parameters
     *
     * @param $product
     * @return array
     */
    public function getAddToCartPostParameters($product)
    {
        return $this->listProductBlock->getAddToCartPostParams($product);
    }

    /**
     * Get rating summary
     *
     * @param $product
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getRatingSummary($product)
    {
        $this->reviewFactory->create()->getEntitySummary($product, $this->storeManager->getStore()->getId());
        $ratingSummary = $product->getRatingSummary()->getRatingSummary();
        return $ratingSummary;
    }

    /**
     * Get reviews count
     *
     * @param $product
     * @return mixed
     */
    public function getReviewsCount($product)
    {
        $_reviewCount = $product->getRatingSummary()->getReviewsCount();
        return $_reviewCount;
    }

    /**
     * Product Price with HTML
     *
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getPrice(\Magento\Catalog\Model\Product $product)
    {
        $priceRender = $this->getLayout()->getBlock('product.price.render.default');
        if (!$priceRender) {
            $priceRender = $this->getLayout()->createBlock(
                \Magento\Framework\Pricing\Render::class,
                'product.price.render.default',
                ['data' => ['price_render_handle' => 'catalog_product_prices']]
            );
        }

        $price = '';
        if ($priceRender) {
            $price = $priceRender->render(
                \Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE,
                $product,
                [
                    'display_minimal_price'  => true,
                    'use_link_for_as_low_as' => true,
                    'zone' => \Magento\Framework\Pricing\Render::ZONE_ITEM_LIST
                ]
            );
        }

        return $price;
    }

    /**
     * Get cart parameter name
     *
     * @return string
     */
    public function getCartParamNameURLEncoded()
    {
        return Action::PARAM_NAME_URL_ENCODED;
    }
}
