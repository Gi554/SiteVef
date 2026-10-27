@props(['label'])
<div style="margin:0 0 22px;">
    <p style="margin:0 0 5px;color:#64748b;font-size:11px;line-height:18px;font-weight:bold;letter-spacing:0.6px;text-transform:uppercase;">{{ $label }}</p>
    <div style="margin:0;color:#172554;font-size:15px;line-height:24px;overflow-wrap:anywhere;word-break:break-word;">{{ $slot }}</div>
</div>
