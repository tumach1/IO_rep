<?php

namespace models;

class PasswordResetTuple
{
    private int $readerId;
    private String $token;
    private String $sessionToken;

    public function getSessionToken(): string
    {
        return $this->sessionToken;
    }

    public function setSessionToken(string $sessionToken): void
    {
        $this->sessionToken = $sessionToken;
    }

    private String $timestamp;

    /**
     * @param int $readerId
     * @param String $token
     * @param String $timestamp
     */
    public function __construct(int $readerId, string $token, string $cookieToken, string $timestamp)
    {
        $this->readerId = $readerId;
        $this->token = $token;
        $this->sessionToken = $cookieToken;
        $this->timestamp = $timestamp;
    }

    public function getReaderId(): int
    {
        return $this->readerId;
    }

    public function setReaderId(int $readerId): void
    {
        $this->readerId = $readerId;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    public function setTimestamp(string $timestamp): void
    {
        $this->timestamp = $timestamp;
    }
}