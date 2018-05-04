<?php

namespace Vindi;

class ImportBatch extends Resource
{
    /**
     * The endpoint that will hit the API.
     *
     * @return string
     */
    public function endPoint()
    {
        return 'import_batches';
    }

    /**
     * @param string $filePath
     * @param array $options
     *
     * @return mixed
     * @throws Exceptions\RateLimitException
     * @throws Exceptions\RequestException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function import($filePath, array $options)
    {
        $file = new \SplFileObject($filePath);
        $requestParams = $this->buildRequestParams($file, $options);

        return $this->apiRequester->request('POST', $this->url(), $requestParams);
    }

    /**
     * @param \SplFileObject $file
     * @param array $options
     *
     * @return array
     */
    private function buildRequestParams(\SplFileObject $file, array $options)
    {
        $multipart = [
            [
                'name' =>'batch',
                'contents' => $file,
                'filename' => $file->getFilename()
            ]
        ];

        foreach ($options as $key => $value) {
            $multipart[] = [
                'name'      => $key,
                'contents'  => $value
            ];
        }

        return compact('multipart');
    }
}
