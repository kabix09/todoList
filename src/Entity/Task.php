<?php
namespace App\Entity;

final class Task extends Base
{
    const TABLE_NAME = "task";
    const STATUS = ["prepared", "planned", "active", "paused", "finished"];

    const MAPPING = [
        "id" => "id",
        "title" => "title",
        "content" => "content",
        "create_date" => "createDate",
        "author" => "author",
        "owner" => "owner",
        "start_date" => "startDate",
        "target_end_date" => "targetEndDate",
        "status" => "status"
    ];

    private string $title;
    private string $content;
    private string $createDate;
    private string $author;
    private string $owner;
    private ?string $startDate;
    private ?string $targetEndDate;
    private string $status;

    public function __construct()
    {
        $this->title = "";
        $this->content = "";
        $this->createDate = "";
        $this->author = "";
        $this->owner = "";
        $this->startDate = NULL;
        $this->targetEndDate = NULL;
        $this->status = "";
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
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getCreateDate(): string
    {
        return $this->createDate;
    }

    /**
     * @param string $createDate
     */
    public function setCreateDate(string $createDate): void
    {
        $this->createDate = $createDate;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @param string $author
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getOwner(): string
    {
        return $this->owner;
    }

    /**
     * @param string $owner
     */
    public function setOwner(string $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return string|null
     */
    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    /**
     * @param string|null $startDate
     */
    public function setStartDate(?string $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return string|null
     */
    public function getTargetEndDate(): ?string
    {
        return $this->targetEndDate;
    }

    /**
     * @param string|null $targetEndDate
     */
    public function setTargetEndDate(?string $targetEndDate): void
    {
        $this->targetEndDate = $targetEndDate;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}