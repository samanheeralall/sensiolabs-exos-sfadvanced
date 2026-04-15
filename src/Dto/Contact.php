<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 5)]
    private string $name;

    #[Assert\NotBlank()]
    #[Assert\Email()]
    private string $email;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 5)]
    private string $subject;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 15)]
    private string $message;

    private ?\DateTimeImmutable $sentAt = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Contact
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): Contact
    {
        $this->subject = $subject;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): Contact
    {
        $this->message = $message;
        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): Contact
    {
        $this->sentAt = $sentAt;

        return $this;
    }
}
