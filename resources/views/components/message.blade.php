@if (session('success'))
    <div class="w-full py-4 flex justify-center align-middle" style="background-color: lightgreen; border-radius: 5px;">
        <h3>{{session('success')}}</h3>
    </div>
@endif

@if (session('error'))
    <div class="w-full py-4 flex justify-center align-middle bg-red-500">
        <h3>{{session('error')}}</h3>
    </div>
@endif