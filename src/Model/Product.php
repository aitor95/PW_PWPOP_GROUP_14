<?php

namespace PwPop\Model;

use DateTime;

final class Product{

    /** @var int */
    private $id;

    /** @var string */
    private $username;

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var int */
    private $price;

    /** @var DateTime */
    private $category;

    /** @var string */
    private $productImg;

    /**
     * User constructor.
     * @param int $id
     * @param string $email
     * @param string $title
     * @param string $description
     * @param int $price
     * @param DateTime $category
     * @param string $productImg
     */
    public function __construct(
        int $id,
        string $username,
        string $title,
        string $description,
        int $price,
        string $category,
        string $productImg
    ) {
        $this->id = $id;
        $this->username = $username;
        $this->title = $title;
        $this->description = $description;
        $this->price = $price;
        $this->category = $category;
        $this->productImg = $productImg;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return DateTime
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param DateTime $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getProductImg(): string
    {
        return $this->productImg;
    }

    /**
     * @param string $productImg
     */
    public function setProductImg(string $productImg): void
    {
        $this->productImg = $productImg;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }




}
