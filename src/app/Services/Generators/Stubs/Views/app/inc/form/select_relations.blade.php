
<div class="form-group">
    <label>{{__('admin.form.'.$relationName)}}</label>
@foreach($relationsList as $relation)

        <div class="checkbox">
            <label>
                <input type="hidden" name="{{ $relationName }}[]" value="0">
                <input type="checkbox" value="{{ $relation->id }}"
                       name="{{ $relationName }}[]"
                       @if($item->$relationName->contains($relation->id)) ) checked="checked" @endif
                >
                {{ $relation->$columnName }}
            </label>
        </div>

@endforeach
</div>
