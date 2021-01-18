<?php


namespace App\Service;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    /**
     * ResponseFactory constructor.
     * @param bool $success
     * @param $data
     * @param int $statusCode
     * @param int|null $currentPage
     * @param int|null $itemsPerPage
     */
    public function __construct(
        bool $success,
        $data,
        int $statusCode = Response::HTTP_OK,
        ?int $currentPage = null,
        ?int $itemsPerPage = null
    )
    {
        $this->success = $success;
        $this->data = $data;
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
        $this->statusCode = $statusCode;
    }

    /**
     * @param \Throwable $error
     * @param int $statusCode
     * @return static
     */
    public static function fromError(\Throwable $error, int $statusCode = HTTP_INTERNAL_SERVER_ERROR): self
    {
        return new self(
            false,
            ['message' => $error->getMessage()],
            $statusCode);
    }

    /**
     * @return JsonResponse
     */
    public function getResponse(): JsonResponse
    {
        $responseContent = [
            "success"=>$this->success,
            "currentPage"=>$this->currentPage,
            "itemsPerPage"=>$this->itemsPerPage,
            "data"=>$this->data
        ];

        if (is_null($this->currentPage)) {
            unset($responseContent["currentPage"]);
            unset($responseContent["itemsPerPage"]);
        }

        if (is_null($this->data)) {
            unset($responseContent["data"]);
        }

        return new JsonResponse($responseContent, $this->statusCode);
    }
}