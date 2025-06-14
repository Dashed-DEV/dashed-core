<section class="border-t border-primary-200 bg-primary-500 @if($data['top_margin'] ?? false) pt-16 sm:pt-24 @endif @if($data['bottom_margin'] ?? false) pb-16 sm:pb-24 @endif">
    <x-container :show="$data['in_container'] ?? true">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-8 lg:gap-x-0 gap-y-8">
            @foreach($data['usps'] as $usp)
                <div
                    data-aos="fade-right" data-aos-delay="{{ 300 * $loop->iteration }}"
                    class="text-center md:flex md:items-start md:text-left lg:block lg:text-center @if(!$loop->first) border-l-2 border-primary-800 pl-4 @else lg:mr-4 border-l-2 border-primary-800 lg:border-l-0 pl-4 lg:pl-0 @endif">
                    <div class="md:shrink-0">
                        <div class="flow-root usp-icon text-primary-800">
                            <x-icon :name="$usp['icon']" />
                        </div>
                    </div>
                    <div class="mt-6 md:ml-4 md:mt-0 lg:ml-0 lg:mt-6 text-white text-left">
                        <h2 class="text-base font-medium">{{ $usp['title'] }}</h2>
                        <div class="mt-2 text-sm">
                            {!!  cms()->convertToHtml($usp['subtitle']) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </x-container>
</section>
