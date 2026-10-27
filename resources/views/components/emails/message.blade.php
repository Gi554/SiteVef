@props(['label', 'text'])
<p style="margin:2px 0 12px;color:#172554;font-size:16px;line-height:24px;font-weight:bold;">{{ $label }}</p>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="width:100%;table-layout:fixed;margin-bottom:16px;">
    <tr><td bgcolor="#f8fafc" style="padding:20px;border-left:3px solid #38bdf8;background-color:#f8fafc;color:#334155;font-size:15px;line-height:26px;overflow-wrap:anywhere;word-break:break-word;">{!! nl2br(e($text)) !!}</td></tr>
</table>
