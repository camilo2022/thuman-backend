<?php

declare(strict_types=1);

namespace App\Application\PersonUseCase;

use App\Domain\Person\Contracts\PersonContract;
use App\Domain\Person\File;
use App\Domain\Person\Person;
use App\Domain\Person\Values\FileExtension;
use App\Domain\Person\Values\FileFileSubtypeId;
use App\Domain\Person\Values\FileFileTypeId;
use App\Domain\Person\Values\FileId;
use App\Domain\Person\Values\FileMetadata;
use App\Domain\Person\Values\FileMime;
use App\Domain\Person\Values\FileModelId;
use App\Domain\Person\Values\FileModelType;
use App\Domain\Person\Values\FileName;
use App\Domain\Person\Values\FilePath;
use App\Domain\Person\Values\FileSize;
use App\Domain\Person\Values\PersonAddress;
use App\Domain\Person\Values\PersonBirthCityId;
use App\Domain\Person\Values\PersonBirthDate;
use App\Domain\Person\Values\PersonBloodTypeId;
use App\Domain\Person\Values\PersonCityId;
use App\Domain\Person\Values\PersonDialingCountryId;
use App\Domain\Person\Values\PersonDocumentIssueCityId;
use App\Domain\Person\Values\PersonDocumentIssueDate;
use App\Domain\Person\Values\PersonDocumentNumber;
use App\Domain\Person\Values\PersonDocumentTypeId;
use App\Domain\Person\Values\PersonEmail;
use App\Domain\Person\Values\PersonEmailVerifiedAt;
use App\Domain\Person\Values\PersonFirstLastName;
use App\Domain\Person\Values\PersonFirstName;
use App\Domain\Person\Values\PersonGenderId;
use App\Domain\Person\Values\PersonId;
use App\Domain\Person\Values\PersonMaritalStatusId;
use App\Domain\Person\Values\PersonNeighborhood;
use App\Domain\Person\Values\PersonPhoneNumber;
use App\Domain\Person\Values\PersonPhoneNumberVerifiedAt;
use App\Domain\Person\Values\PersonSecondLastName;
use App\Domain\Person\Values\PersonSecondName;
use App\Domain\Person\Values\PersonSexId;
use App\Models\Person as PersonModel;
use App\Http\Requests\Person\PersonUpdateRequest;
use Illuminate\Support\Facades\Storage;

final class PersonUpdateUseCase
{
    private $personContract;

    public function __construct(PersonContract $personContract)
    {
        $this->personContract = $personContract;
    }

    public function __invoke(PersonUpdateRequest $request, PersonId $personId): Person
    {
        foreach (['photo', 'file', 'signature'] as $key) {
            if ($request->hasFile("$key.file")) {
                $this->personContract->file(
                    new File(
                        new FileId(null),
                        new FileModelId($personId->value()),
                        new FileModelType(PersonModel::class),
                        new FileFileTypeId($request->integer("$key.file_type_id")),
                        new FileFileSubtypeId($request->integer("$key.file_subtype_id")),
                        new FileName(pathinfo($request->file("$key.file")->getClientOriginalName(), PATHINFO_FILENAME)),
                        new FilePath(Storage::disk('public')->putFile("Person", $request->file("$key.file"))),
                        new FileMime($request->file("$key.file")->getMimeType()),
                        new FileExtension($request->file("$key.file")->getClientOriginalExtension()),
                        new FileSize($request->file("$key.file")->getSize()),
                        new FileMetadata([
                            'client_original_name' => $request->file("$key.file")->getClientOriginalName(),
                            'client_original_ext' => $request->file("$key.file")->getClientOriginalExtension(),
                            'client_mime' => $request->file("$key.file")->getClientMimeType(),
                            'mime' => $request->file("$key.file")->getMimeType(),
                            'size' => $request->file("$key.file")->getSize(),
                            'error' => $request->file("$key.file")->getError(),
                            'tmp_name' => $request->file("$key.file")->getPathname(),
                            'real_path' => $request->file("$key.file")->getRealPath() ?: null,
                            'file_exists' => file_exists($request->file("$key.file")->getPathname()),
                            'is_readable' => is_readable($request->file("$key.file")->getPathname()),
                            'is_writable' => is_writable($request->file("$key.file")->getPathname()),
                            'hash_md5' => @md5_file($request->file("$key.file")->getPathname()),
                            'hash_sha1' => @sha1_file($request->file("$key.file")->getPathname()),
                            'hash_sha256' => @hash_file('sha256', $request->file("$key.file")->getPathname()),
                            'image_info' => ($info = @getimagesize($request->file("$key.file")->getPathname())) ?: null,
                            'width' => $info[0] ?? null,
                            'height' => $info[1] ?? null,
                            'orientation' => (isset($info[0], $info[1]))
                                                        ? ($info[0] > $info[1] ? 'horizontal' : 'vertical')
                                                        : null,
                            'stat' => ($request->file("$key.file")->getPathname() && file_exists($request->file("$key.file")->getPathname())) ? @stat($request->file("$key.file")->getPathname()) : null,
                        ])
                    )
                );
            }
        }

        return $this->personContract->update(
            new Person(
                new PersonId($personId->value()),
                new PersonFirstName($request->input('first_name')),
                new PersonSecondName($request->input('second_name')),
                new PersonFirstLastName($request->input('first_last_name')),
                new PersonSecondLastName($request->input('second_last_name')),
                new PersonBirthDate($request->input('birth_date')),
                new PersonBirthCityId($request->integer('birth_city_id')),
                new PersonDocumentNumber($request->input('document_number')),
                new PersonDocumentIssueCityId($request->integer('document_issue_city_id')),
                new PersonDocumentIssueDate($request->input('document_issue_date')),
                new PersonCityId($request->integer('city_id')),
                new PersonAddress($request->input('address')),
                new PersonNeighborhood($request->input('neighborhood')),
                new PersonDialingCountryId($request->integer('dialing_country_id')),
                new PersonPhoneNumber($request->input('phone_number')),
                new PersonPhoneNumberVerifiedAt($request->input('phone_number_verified_at', null)),
                new PersonEmail($request->input('email')),
                new PersonEmailVerifiedAt($request->input('email_verified_at', null)),
                new PersonDocumentTypeId($request->integer('document_type_id')),
                new PersonSexId($request->integer('sex_id')),
                new PersonGenderId($request->integer('gender_id')),
                new PersonBloodTypeId($request->integer('blood_type_id')),
                new PersonMaritalStatusId($request->integer('marital_status_id'))
            ),
            $personId
        );
    }
}
