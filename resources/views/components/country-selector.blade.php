<select
    wire:model="state.country"
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
>
    <option value="">{{ __('Select Country') }}</option>
    @foreach(config('countries') as $code => $name)
        <option value="{{ $code }}" @selected($code === $this->user->country)>
            {{ $name }}
        </option>
    @endforeach
</select>
