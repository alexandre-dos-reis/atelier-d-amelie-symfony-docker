<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Artwork;
use App\Entity\Category;
use App\Entity\ImageProduct;
use App\Entity\Product;
use App\Entity\ShopCategory;
use App\Entity\ShopSubCategory;
use App\Entity\Purchase;
use App\Entity\PurchaseAddress;
use App\Entity\PurchaseItem;
use App\Entity\ShippingCost;
use App\Entity\AppSettings;
use Symfony\Component\Uid\Uuid;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use App\Repository\ShopSubCategoryRepository;
use Symfony\Component\Finder\Finder;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    protected $shopSubCategoryRepository;
    protected $categoryRepository;
    protected $productRepository;
    protected $purchaseRepository;

    public function __construct(
        SluggerInterface $slugger,
        ShopSubCategoryRepository $shopSubCategoryRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        PurchaseRepository $purchaseRepository
    ) {
        $this->slugger = $slugger;
        $this->shopSubCategoryRepository = $shopSubCategoryRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->purchaseRepository = $purchaseRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // SHOP CATEGORIES

        $shopCatsTree = [
            'Oeuvres originales' => [
                '1' => 'Enluminures',
                '2' => 'Calligraphies',
                '3' => 'Cartes',
                '3' => 'Marque-pages'
            ],
            'Reproductions' => [
                '1' => 'Marques-Pages',
                '2' => 'Cartes-postales',
                '3' => 'Cartes A5',
                '4' => 'Cartes A4',
                '5' => 'Autres formats'
            ]
        ];

        $i = 0;
        foreach ($shopCatsTree as $shopCatName => $shopSubCartsList) {

            $i++;
            $shopCat = (new ShopCategory)
                ->setName($shopCatName)
                ->setSlug(strtolower($this->slugger->slug($shopCatName)))
                ->setDisposition($i);
            $manager->persist($shopCat);

            foreach ($shopSubCartsList as $key => $shopSubCatName) {

                $shopSubCat = (new ShopSubCategory)
                    ->setName($shopSubCatName)
                    ->setSlug(strtolower($this->slugger->slug($shopSubCatName)))
                    ->setDisposition($key)
                    ->setShopCategory($shopCat);
                $manager->persist($shopSubCat);
            }
        }
        $manager->flush();

        // ARTWORK CATEGORIES

        $cats = [
            '1' => 'Calligraphies',
            '2' => 'Enluminures',
            '3' => 'Marque-pages',
            '4' => 'Evènements',
            '5' => 'Autres techniques'
        ];

        foreach ($cats as $key => $catName) {
            $cat = new Category;
            $cat->setName($catName)
                ->setSlug(strtolower($catName))
                ->setDescription($faker->text())
                ->setDisposition($key)
                ->setShowInGallery(true);

            $manager->persist($cat);
            $manager->flush();
        }

        // ARTWORKS & PRODUCTS

        $artworkFiles = (new Finder)->files()->in('public/gallery-test');
        $productFiles = (new Finder)->files()->in('public/product-test');

        foreach ($artworkFiles as $file) {

            $artwork = new Artwork;
            $artwork
                ->setName($faker->sentence(rand(1, 3)))
                ->setSlug(strtolower($this->slugger->slug($artwork->getName())))
                ->setDescription($faker->text())
                ->setShowInGallery($faker->randomElement([true, false]))
                ->setShowInPortfolio($faker->randomElement([true, false]))
                ->setImageOriginal($file->getFilename())
                ->setCreatedAt($faker->dateTimeBetween('-6 month', 'now'))
                ->setPublishedAt($faker->dateTimeBetween('-6 month', 'now'))
                ->addCategory($faker->randomElement($this->categoryRepository->findAll()));

            for ($p = 0; $p < 2; $p++) {

                $product = new Product;
                $product
                    ->setArtwork($artwork)
                    ->setDescription($faker->text())
                    ->setUpdatedAt($faker->dateTimeBetween('-6 month', 'now'))
                    ->setPrice($faker->randomDigitNotZero() * 10000)
                    ->setStock($faker->randomDigitNotZero())
                    ->setForSale($faker->randomElement([true, false]))
                    ->setHeight($faker->randomDigitNotZero() * 10)
                    ->setWidth($faker->randomDigitNotZero() * 10)
                    ->setShopSubCategory($faker->randomElement($this->shopSubCategoryRepository->findAll()));

                $k = 1;
                foreach ($productFiles as $file) {
                    $imageProduct = new ImageProduct;
                    $imageProduct
                        ->setDisposition($k++)
                        ->setImageOriginal($file->getFilename())
                        ->setUpdatedAt($faker->dateTimeBetween('-6 month', 'now'));

                    $manager->persist($imageProduct);

                    $product->addImageProduct($imageProduct);
                }
                $manager->persist($product);
            }
            $manager->persist($artwork);
        }
        $manager->flush();

        // Purchases
        for ($p = 0; $p < 19; $p++) {

            $purchase = new Purchase();
            $purchase
                ->setUuid()
                ->setStripeId("test")
                ->setPurchasedAt(new \DateTimeImmutable())
                ->setStatus($faker->randomElement(Purchase::STATUS_ARRAY))
                ->setEmail($faker->email)
                ->setTrackingNumber(Uuid::v4())
                ->setInsuranceCost(500)
                ->setWeightCost(1200);

            $address = (new PurchaseAddress())
                ->setType($faker->randomElement(PurchaseAddress::ADDRESS_TYPES))
                ->setFullname($faker->name)
                ->setAddress($faker->streetAddress)
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry('FR')
                ->setPhone($faker->phoneNumber);

            $manager->persist($address);
            $purchase->addPurchaseAddress($address);

            if ($address->getType() === PurchaseAddress::ADDR_TYPE_BILLING) {

                $addressDelibery = (new PurchaseAddress())
                    ->setType(PurchaseAddress::ADDR_TYPE_DELIVERY)
                    ->setFullname($faker->name)
                    ->setAddress($faker->streetAddress)
                    ->setPostalCode($faker->postcode)
                    ->setCity($faker->city)
                    ->setCountry('FR');

                $manager->persist($addressDelibery);
                $purchase->addPurchaseAddress($addressDelibery);
            }

            if ($address->getType() === PurchaseAddress::ADDR_TYPE_DELIVERY) {

                $address->setPhone(null);

                $addressBilling = (new PurchaseAddress())
                    ->setType(PurchaseAddress::ADDR_TYPE_BILLING)
                    ->setFullname($faker->name)
                    ->setAddress($faker->streetAddress)
                    ->setPostalCode($faker->postcode)
                    ->setCity($faker->city)
                    ->setCountry('FR')
                    ->setPhone($faker->phoneNumber);

                $manager->persist($addressBilling);
                $purchase->addPurchaseAddress($addressBilling);
            }

            $manager->persist($purchase);
            $manager->flush();

            $products = $this->productRepository->findAll();
            $selectedProducts = $faker->randomElements($products, mt_rand(1, 5));

            /** @var Product $product */
            foreach ($selectedProducts as $product) {

                $purchaseItem = new PurchaseItem;
                $purchaseItem
                    ->setProduct($product)
                    ->setPurchase($purchase)
                    ->setProductName($product->getShopSubCategory()->getShopCategory()->getName() . ' - ' . $product->getShopSubCategory()->getName() . ' - ' . $product->getArtwork()->getName())
                    ->setProductPrice($product->getPrice())
                    ->setQty(mt_rand(1, $product->getStock()));

                $manager->persist($purchaseItem);
            }
            $manager->flush();
        }

        // Update Purchase total, ne marche pas...
        foreach ($this->purchaseRepository->findAll() as $p) {

            $total = 0;
            foreach ($p->getPurchaseItems() as $item) {
                /** @var PurchaseItem $item */
                $total += $item->getProductPrice() * $item->getQty();
            }

            $p->setTotal($total);
            $manager->persist($p);
        }

        $manager->flush();

        // Shop Settings
        $shopSettings = new AppSettings();
        $shopSettings
            ->setCgv('<h1>CGV</h1>');

        $manager->persist($shopSettings);
        $manager->flush();

        // ShippingCost
        $shippingCosts = [
            [0, 3000, 500, 0],
            [3000, 5000, 500, 250],
            [5000, 20000, 500, 400],
            [20000, 30000, 500, 500],
            [30000, 40000, 500, 600],
            [40000, 50000, 500, 700],
            [50000, 60000, 500, 800],
            [60000, 70000, 500, 900],
            [70000, 80000, 500, 1000],
            [80000, 90000, 500, 1100],
            [90000, 100000, 500, 1200]
        ];

        foreach ($shippingCosts as $c) {
            $shippingCost = (new ShippingCost)
                ->setMax($c[1])
                ->setWeightCost($c[2])
                ->setInsurance($c[3]);

            $manager->persist($shippingCost);
        }
        $manager->flush();
    }
}
