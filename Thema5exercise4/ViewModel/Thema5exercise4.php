<?php
namespace Perspective\Thema5exercise4\ViewModel;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Thema5exercise4 implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var Magento\Bundle\Api\ProductLinkManagementInterface
     */
    private $productLinkManagement;

    /**
     * @var Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $_productRepository;

    public function __construct(
        \Magento\Bundle\Api\ProductLinkManagementInterface $productLinkManagement,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    )
    {
        $this->productLinkManagement = $productLinkManagement;
        $this->_productRepository = $productRepository;
        
    }
    public function getChildrenItems()
    {
        $sku = '24-WG080'; // Dynamic SKU
        try {
            $items = $this->productLinkManagement->getChildren($sku);
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }

        return $items;
    }
    public function getChildItemsFromGroupedProduct(string $sku): array
    {
        try {
            $product = $this->_productRepository->get($sku);
        } catch (NoSuchEntityException $noEntityException) {
            throw new LocalizedException(
                __('Please correct the product SKU.'),
                $noEntityException
            );
        }
        return $product->getTypeInstance()->getAssociatedProducts($product);
    }

}
