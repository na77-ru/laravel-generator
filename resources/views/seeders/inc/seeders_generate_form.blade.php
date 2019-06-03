
<div class="form-group">
    <label for="name{{ $id }}">{{__('table name') . $id}}</label>
    <input name="name{{ $id }}" value="{{ old('name' . $id ) }}"
           id="name"
           type="text"
           class="form-control"
           minlength="3"
    >
</div>


