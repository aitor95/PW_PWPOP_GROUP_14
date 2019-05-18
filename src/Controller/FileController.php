<?php

namespace PwPop\Controller;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\UploadedFileInterface;

final class FileController
{
    private const UPLOADS_DIR = __DIR__ . '/../../public/assets/img/Uploads';

    private const UNEXPECTED_ERROR = "An unexpected error occurred uploading the file '%s'...";

    private const INVALID_EXTENSION_ERROR = "The received file extension '%s' is not valid";

    private const ALLOWED_EXTENSIONS = ['jpg', 'png', 'jpeg'];


    public function uploadAction(Request $request, Response $response, string $user):string
    {
        $uploadedFiles = $request->getUploadedFiles();

        $errors = [];
        $name = '';

        /* @var UploadedFileInterface $uploadedFile */
        foreach ($uploadedFiles['files'] as $uploadedFile) {

            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(self::UNEXPECTED_ERROR, $uploadedFile->getClientFilename());
                continue;
            }

            $name = $uploadedFile->getClientFilename();

            $fileInfo = pathinfo($name);

            $format = $fileInfo['extension'];

            if (!$this->isValidFormat($format)) {
                $errors[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
                continue;
            }

            $name = "Profile_". $user . "." . $format;
            // We generate a custom name here instead of using the one coming form the form
            $uploadedFile->moveTo(self::UPLOADS_DIR . DIRECTORY_SEPARATOR . $name);
        }

        return $name;
    }

    private function isValidFormat(string $extension): bool {

        return in_array($extension, self::ALLOWED_EXTENSIONS, true);

    }

    public function uploadProductAction(Request $request, Response $response, string $username, int $id):string
    {
        $uploadedFiles = $request->getUploadedFiles();

        $errors = [];
        $name = '';
        //ens guardem la id de la primera imatge com a nom de la carpeta nova
        $num = $id;
        $folder='';

        /* @var UploadedFileInterface $uploadedFile */
        foreach ($uploadedFiles['files'] as $uploadedFile) {

            if ($uploadedFile->getError() !== UPLOAD_ERR_OK) {
                $errors[] = sprintf(self::UNEXPECTED_ERROR, $uploadedFile->getClientFilename());
                continue;
            }

            $name = $uploadedFile->getClientFilename();

            $fileInfo = pathinfo($name);

            $format = $fileInfo['extension'];

            if (!$this->isValidFormat($format)) {
                $folder='error';
                $errors[] = sprintf(self::INVALID_EXTENSION_ERROR, $format);
                continue;
            }

            if($folder==''){
                //Creamos la nueva carpeta para el producto
                $folder = "Product_".$username.$num. "." . $format;
                mkdir(self::UPLOADS_DIR. DIRECTORY_SEPARATOR. $folder, 0777, true);
            }

            $name = "Product_". $username.$id . "." . $format;
            // We generate a custom name here instead of using the one coming form the form
            $uploadedFile->moveTo(self::UPLOADS_DIR. DIRECTORY_SEPARATOR. $folder . DIRECTORY_SEPARATOR . $name);
            $id++;
        }

        return $folder;
    }
 }
