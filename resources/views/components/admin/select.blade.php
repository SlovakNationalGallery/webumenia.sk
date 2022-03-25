<select
    {{ $attributes->merge(['class' =>'tw-rounded-md tw-border tw-border-gray-300 tw-px-3 tw-py-1.5 tw-shadow-inner focus:tw-border-sky-400 focus:tw-shadow-[inset_0_1px_1px_rgb(0,0,0,0.2),0_0_8px_rgb(102,175,233,0.6)]']) }}>
    {{ $slot }}
</select>
