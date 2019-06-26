<?php

declare(strict_types=1);

namespace Sylius\ShopApiPlugin\Handler\Product;

use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Repository\ProductReviewRepositoryInterface;
use Sylius\Component\Review\Factory\ReviewFactoryInterface;
use Sylius\ShopApiPlugin\Command\Product\AddProductReviewBySlug;
use Sylius\ShopApiPlugin\Provider\ProductReviewerProviderInterface;
use Webmozart\Assert\Assert;

final class AddProductReviewBySlugHandler
{
    /** @var ProductReviewRepositoryInterface */
    private $productReviewRepository;

    /** @var ChannelRepositoryInterface */
    private $channelRepository;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ProductReviewerProviderInterface */
    private $productReviewerProvider;

    /** @var ReviewFactoryInterface */
    private $reviewFactory;

    public function __construct(
        ProductReviewRepositoryInterface $productReviewRepository,
        ChannelRepositoryInterface $channelRepository,
        ProductRepositoryInterface $productRepository,
        ProductReviewerProviderInterface $productReviewerProvider,
        ReviewFactoryInterface $reviewFactory
    ) {
        $this->productReviewRepository = $productReviewRepository;
        $this->channelRepository = $channelRepository;
        $this->productRepository = $productRepository;
        $this->productReviewerProvider = $productReviewerProvider;
        $this->reviewFactory = $reviewFactory;
    }

    public function __invoke(AddProductReviewBySlug $addProductReviewBySlug): void
    {
        /** @var ChannelInterface $channel */
        $channel = $this->channelRepository->findOneByCode($addProductReviewBySlug->channelCode());

        Assert::notNull($channel, 'Channel not found.');

        $product = $this->productRepository->findOneByChannelAndSlug(
            $channel,
            $channel->getDefaultLocale()->getCode(),
            $addProductReviewBySlug->productSlug()
        );

        Assert::notNull($product, 'Product not found.');

        $productReviewer = $this->productReviewerProvider->provide($addProductReviewBySlug->email());

        $productReview = $this->reviewFactory->createForSubjectWithReviewer($product, $productReviewer);

        $productReview->setComment($addProductReviewBySlug->comment());
        $productReview->setRating($addProductReviewBySlug->rating());
        $productReview->setTitle($addProductReviewBySlug->title());

        $this->productReviewRepository->add($productReview);
    }
}
