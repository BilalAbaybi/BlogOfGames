<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

final class JeuxImageUploader
{
    public function __construct(
        #[Autowire('%kernel.project_dir%/public/uploads/jeux')]
        private string $targetDirectory,
        private SluggerInterface $slugger,
    ) {
    }

    /** @param list<UploadedFile> $files
     *  @return list<string>
     */
    public function uploadAll(array $files): array
    {
        if (!is_dir($this->targetDirectory) && !mkdir($this->targetDirectory, 0775, true) && !is_dir($this->targetDirectory)) {
            throw new \RuntimeException('Impossible de créer le dossier de la galerie des jeux.');
        }

        $filenames = [];
        foreach ($files as $file) {
            $extension = $file->guessExtension();
            if (null === $extension) {
                throw new \RuntimeException('Impossible de déterminer le format de cette image.');
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = $this->slugger->slug($originalName)->lower();
            $filename = $safeName.'-'.bin2hex(random_bytes(8)).'.'.$extension;

            $file->move($this->targetDirectory, $filename);
            $filenames[] = $filename;
        }

        return $filenames;
    }
}
