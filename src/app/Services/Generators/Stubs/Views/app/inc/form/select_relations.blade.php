

@foreach($relsList as $rels)
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="hidden" name="{{ $relsName }}[]" value="0">
                <input type="checkbox" value="{{ $rels->id  }}"
                       name="{{ $relsName }}[]"
                       @if($itemRels->contains($rels->id)) ) checked="checked" @endif
                >
                @if(isset($fieldName)){{ $rels->$fieldName }}
                @elseif($rels->name ) {{ $rels->name }}
                @elseif($rels->title ) {{ $rels->title }}
                @elseif($rels->id ) {{ $rels->id }}
                @endif
            </label>
        </div>
    </div>
@endforeach
