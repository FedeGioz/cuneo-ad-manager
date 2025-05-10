<select
    wire:model="state.country"
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    id="country" name="country"
>
    <option value="">{{ __('Select Country') }}</option>
    @foreach(config('countries') as $code => $name)
        <option value="{{ $code }}"
                @if(auth()->check() && isset($this->user) && $code == $this->user->country) selected @endif>
            {{ $name }}
        </option>
    @endforeach
</select>
