<?
$name = $block->id ? 'blocks' : 'blocks_new';
$class = $block->id ? '' : ' unsaved';
$id = $block->id ? $block->id : '%i%';
$block->settings = json_decode($block->settings, 1);
#Helper::ta($block->settings);
?>
<div class="block clearfix{{ $class }}" data-block_id="{{ $block->id }}">
    <input type="text" name="{{ $name }}[{{ $id }}][name]" value="{{ $block->name }}" placeholder="Название блока" class="block_name">
    @if (Allow::action('pages', 'advanced', true, false))
        <input type="text" name="{{ $name }}[{{ $id }}][slug]" value="{{ $block->slug }}" placeholder="Системное имя" class="block_desc">
        @if (isset($block_templates) && is_array($block_templates) && count($block_templates))
            @if ($block->id)
                <div class="block_tpl">{{ isset($block_templates[$block->template]) ? $block_templates[$block->template] : 'По умолчанию' }}</div>
            @else
                {{ Form::select($name.'['.$id.'][template]', [null => 'По умолчанию'] + $block_templates, null, ['class' => 'block_tpl']) }}
            @endif
        @else
            {{ Form::hidden($name.'['.$id.'][template]', '') }}
        @endif
    @endif
    <input type="hidden" name="{{ $name }}[{{ $id }}][order]" value="{{ is_int($block->order) ? $block->order : '%p%' }}" class="block_order">
    @if (Allow::action('pages', 'advanced', true, false) || @!$block->settings['system_block'])
        <a href="javascript:void(0)" class="remove_block"><i class="fa fa-trash-o"></i> удалить</a>
    @endif
    @if ($block->id)
        <a href="javascript:void(0)" class="edit_block" data-id="{{ $block->id }}"><i class="fa fa-pencil"></i> править</a>
    @endif
</div>
