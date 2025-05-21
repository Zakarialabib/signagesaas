@props(['for' => null])

@error($for)
    <p {{ $attributes->merge(['class' => 'mt-1 text-sm text-red-600 dark:text-red-400']) }}>
        {{ $message }}
    </p>
@enderror
