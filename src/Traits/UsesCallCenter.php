<?php

namespace GreyZero\WebCallCenter\Traits;

trait UsesCallCenter{
    /**
     * Gets the authenticated agent or customer of this user account if morphed in database.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function authenticatable(){
        return $this->morphTo();
    }

    /**
     * Gets the morphed authenticated agent or customer if exists or, this user instance if it's not morphed.
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function getAuthenticatableAttribute(){
        $relation = 'authenticatable';
        return !config('web-call-center.morph_authenticatable')? $this :
            ($this->relationLoaded($relation)? $this->relations[$relation] : $this->getRelationshipFromMethod($relation));
    }

    /**
     * Gets the ID of the authenticated agent or customer.
     *
     * @param int $id The ID of the authenticated agent or customer if morphed in database.
     * @return ?int
     */
    public function getAuthenticatableIdAttribute($id){
        return $id?? $this->Id;
    }

    /**
     * The type of the authenticated user whether an agent or a customer if morphed in database.
     *
     * @param string $type The morph type of the authenticated user whether an agent or a customer if done in database.
     * @return ?string
     */
    public function getAuthenticatableTypeAttribute($type){
        return $type?? match(static::class){
            config('web-call-center.agent_model') => 'agent',
            config('web-call-center.customer_model') => 'customer',
            default => null
        };
    }

    /**
     * Gets the role of the user an "agent" or a "customer".
     *
     * @return string
     */
    public function getRoleAttribute(){
        return $this->authenticatable_type;
    }
}
