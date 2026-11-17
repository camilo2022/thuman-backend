<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase\LanguageProficiencieUseCase;

use App\Domain\Profile\LanguageProficiencie\Contracts\LanguageProficiencieContract;
use App\Domain\Profile\LanguageProficiencie\File;
use App\Domain\Profile\LanguageProficiencie\LanguageProficiencie;
use App\Domain\Profile\LanguageProficiencie\Values\FileExtension;
use App\Domain\Profile\LanguageProficiencie\Values\FileFileSubtypeId;
use App\Domain\Profile\LanguageProficiencie\Values\FileFileTypeId;
use App\Domain\Profile\LanguageProficiencie\Values\FileId;
use App\Domain\Profile\LanguageProficiencie\Values\FileMetadata;
use App\Domain\Profile\LanguageProficiencie\Values\FileMime;
use App\Domain\Profile\LanguageProficiencie\Values\FileModelId;
use App\Domain\Profile\LanguageProficiencie\Values\FileModelType;
use App\Domain\Profile\LanguageProficiencie\Values\FileName;
use App\Domain\Profile\LanguageProficiencie\Values\FilePath;
use App\Domain\Profile\LanguageProficiencie\Values\FileSize;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieLanguageId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieLanguageLevelId;
use App\Domain\Profile\LanguageProficiencie\Values\LanguageProficiencieProfileId;
use App\Models\LanguageProficiencie as LanguageProficiencieModel;
use App\Http\Requests\Profile\LanguageProficiencie\LanguageProficiencieStoreRequest;
use Illuminate\Support\Facades\Storage;

final class LanguageProficiencieStoreUseCase
{
    private $languageProficiencieContract;

    public function __construct(LanguageProficiencieContract $languageProficiencieContract)
    {
        $this->languageProficiencieContract = $languageProficiencieContract;
    }

    public function __invoke(LanguageProficiencieStoreRequest $request): LanguageProficiencie
    {
        $languageProficiencie = $this->languageProficiencieContract->store(
            new LanguageProficiencie(
                new LanguageProficiencieId(null),
                new LanguageProficiencieProfileId($request->integer('profile_id')),
                new LanguageProficiencieLanguageId($request->integer('language_id')),
                new LanguageProficiencieLanguageLevelId($request->integer('language_level_id')),
            )
        );

        if ($request->hasFile('file.file')) {
            $this->languageProficiencieContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($languageProficiencie->id()->value()),
                    new FileModelType(LanguageProficiencieModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Profile/LanguageProficiencie", $request->file('file.file'))),
                    new FileMime($request->file('file.file')->getMimeType()),
                    new FileExtension($request->file('file.file')->getClientOriginalExtension()),
                    new FileSize($request->file('file.file')->getSize()),
                    new FileMetadata([
                        'client_original_name' => $request->file('file.file')->getClientOriginalName(),
                        'client_original_ext' => $request->file('file.file')->getClientOriginalExtension(),
                        'client_mime' => $request->file('file.file')->getClientMimeType(),
                        'mime' => $request->file('file.file')->getMimeType(),
                        'size' => $request->file('file.file')->getSize(),
                        'error' => $request->file('file.file')->getError(),
                        'tmp_name' => $request->file('file.file')->getPathname(),
                        'real_path' => $request->file('file.file')->getRealPath() ?: null,
                        'file_exists' => file_exists($request->file('file.file')->getPathname()),
                        'is_readable' => is_readable($request->file('file.file')->getPathname()),
                        'is_writable' => is_writable($request->file('file.file')->getPathname()),
                        'hash_md5' => @md5_file($request->file('file.file')->getPathname()),
                        'hash_sha1' => @sha1_file($request->file('file.file')->getPathname()),
                        'hash_sha256' => @hash_file('sha256', $request->file('file.file')->getPathname()),
                        'image_info' => ($info = @getimagesize($request->file('file.file')->getPathname())) ?: null,
                        'width' => $info[0] ?? null,
                        'height' => $info[1] ?? null,
                        'orientation' => (isset($info[0], $info[1]))
                                                    ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                    : null,
                        'stat' => ($request->file('file.file')->getPathname() && file_exists($request->file('file.file')->getPathname())) ? @stat($request->file('file.file')->getPathname()) : null,
                    ])
                )
            );
        }

        return $languageProficiencie;
    }
}
