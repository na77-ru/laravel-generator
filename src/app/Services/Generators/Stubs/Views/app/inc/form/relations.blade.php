@for($i = 0; $i < ($max = 2) && $i < $count = count($relations); $i++)
    @if(isset($relationsName)){{ $relations[$i]->$relationsName }}
    @elseif($relations[$i]->name ) {{ $relations[$i]->name }}
    @elseif($relations[$i]->title ) {{ $relations[$i]->title }}
    @elseif($relations[$i]->id ) {{ $relations[$i]->id }}
    @endif
    @if($i+1  == $max && $max < $count){{' ...'}}
    @elseif($i+1  != $count) {{', '}}
    @endif
@endfor
