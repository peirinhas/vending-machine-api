<?php

namespace App\Api\Action\Product;

use App\Exception\Product\BuyProductException;
use App\Exception\Product\ProductStockException;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class Buy
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/products/buy/{id}", methods={"PATCH"})
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');

        $product = $this->productRepository->findOneById($id);

        $wallet = $product->getWallet();
        $cash = $product->getCash();
        $price = $product->getCost();
        $productName = $product->getName();
        $stock = $product->getStock();
        $machine = $product->getMachine();

        if (0 === $stock) {
            ProductStockException::notAreStock();
        }

        if ($wallet < $price) {
            BuyProductException::notMinimumCost($productName);
        }

        $newWallet = $wallet - $price;
        $newCash = $cash + $price;
        $newStock = $stock - 1;

        $product->setWallet($newWallet);
        $product->setCash($newCash);
        $product->setStock($newStock);
        $this->productRepository->updateMachine($machine);
        $this->productRepository->save($product);

        return new JsonResponse(['product' => \sprintf('GET %s', $productName)]);

    }
}
