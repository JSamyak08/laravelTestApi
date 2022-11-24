<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return [
            "id"                    => $this->id,
            "first_name"            => $this->first_name,
            "last_name"             => $this->last_name,
            "role"                  => (int) $this->role,
            "mobile_number"         => $this->mobile_number ? $this->mobile_number : '',
            "email"                 => $this->email,
            "status"                => (int)$this->status,
            "is_verified"           => (int)$this->is_verified,
            "date_of_birth"         => $this->date_of_birth ?? '',
            "profile_avatar"        => $this->profile_avatar ?? '',
            "timezone"              => $this->timezone ?? '',
            "customer_stripe_id"    => $this->customer_stripe_id ?? '',
            "login_type"            => $this->login_type ?? '',
            "login_platform"        => $this->login_platform ?? '',
            "os_version"            => $this->os_version ?? '',
            "application_version"   => $this->application_version ?? '',
            "created_at"            => $this->created_at,
            "updated_at"            => $this->updated_at
        ];
    }
}
