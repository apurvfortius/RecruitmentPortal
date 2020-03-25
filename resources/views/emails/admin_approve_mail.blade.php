Dear {{ $company[0]->company_title }},<br><br>

For the Position of {{ $company[0]->title }} from your company we assigned one candidate <br><br>

{{ $candidate->name }} <br>
Currrenly Working as {{ $candidate->crnnt_desgnation }} <br>
{{ $candidate->total_experience }} Year Experience <br>
<p>
    {{ $body }}
</p>

Best Regards,