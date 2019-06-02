
<div class="form-group">
    <label for="name{{ $id }}">{{__('table name') . $id}}</label>
    <input name="name{{ $id }}" value="{{ old('name' . $id ) }}"
           id="name"
           type="text"
           class="form-control"
           minlength="3"
    >
</div>
<div class="form-group">
    <label for="name{{ $id }}">{{ __('columns_id') }}</label>
    <input name="columns_id" value="{{ old('columns_id' ) }}"
           id="columns_id"
           type="text"
           class="form-control"
           minlength="3"
    >
</div>

@foreach($checkboxes as $column)
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="hidden" name="{{ $column . $id }}" value="0">
                <input type="checkbox" value="1"
                       name="{{ $column . $id  }}" checked="checked">
                {{ $column }}
            </label>
        </div>
    </div>
@endforeach
