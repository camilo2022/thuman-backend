<?php

declare(strict_types=1);

namespace App\Application\ProfileUseCase;

use App\Domain\Profile\Contracts\ProfileContract;
use App\Domain\Profile\File;
use App\Domain\Profile\Profile;
use App\Domain\Profile\Values\FileExtension;
use App\Domain\Profile\Values\FileFileSubtypeId;
use App\Domain\Profile\Values\FileFileTypeId;
use App\Domain\Profile\Values\FileId;
use App\Domain\Profile\Values\FileMetadata;
use App\Domain\Profile\Values\FileMime;
use App\Domain\Profile\Values\FileModelId;
use App\Domain\Profile\Values\FileModelType;
use App\Domain\Profile\Values\FileName;
use App\Domain\Profile\Values\FilePath;
use App\Domain\Profile\Values\FileSize;
use App\Domain\Profile\Values\ProfileId;
use App\Domain\Profile\Values\ProfilePersonId;
use App\Domain\Profile\Values\ProfileDescription;
use App\Domain\Profile\Values\ProfileTitle;
use App\Models\Profile as ProfileModel;
use App\Http\Requests\Profile\ProfileStoreRequest;
use Illuminate\Support\Facades\Storage;

final class ProfileStoreUseCase
{
    private $profileContract;

    public function __construct(ProfileContract $profileContract)
    {
        $this->profileContract = $profileContract;
    }

    public function __invoke(ProfileStoreRequest $request): Profile
    {
        $profile = $this->profileContract->store(
            new Profile(
                new ProfileId(null),
                new ProfilePersonId($request->integer('person_id')),
                new ProfileTitle($request->input('title')),
                new ProfileDescription($request->input('description'))
            )
        );

        if ($request->hasFile('file.file')) {
            $this->profileContract->file(
                new File(
                    new FileId(null),
                    new FileModelId($profile->id()->value()),
                    new FileModelType(ProfileModel::class),
                    new FileFileTypeId($request->integer('file.file_type_id')),
                    new FileFileSubtypeId($request->integer('file.file_subtype_id')),
                    new FileName(pathinfo($request->file('file.file')->getClientOriginalName(), PATHINFO_FILENAME)),
                    new FilePath(Storage::disk('public')->putFile("Profile", $request->file('file.file'))),
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

        return $profile;
    }
}
