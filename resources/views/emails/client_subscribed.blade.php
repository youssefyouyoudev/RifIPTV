<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subjectLine }}</title>
</head>
<body style="margin:0; padding:0; background:#eef6ff; font-family:'DM Sans', Arial, sans-serif; color:#0f172a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#eef6ff; padding:32px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:720px; background:#08111f; border-radius:28px; overflow:hidden; border:1px solid rgba(148,163,184,0.22); box-shadow:0 24px 60px rgba(15,23,42,0.18);">
                    <tr>
                        <td style="padding:32px; background:linear-gradient(135deg, rgba(30,152,215,0.24), rgba(122,199,12,0.14)); border-bottom:1px solid rgba(148,163,184,0.18);">
                            <div style="display:inline-block; padding:8px 14px; border-radius:999px; background:rgba(255,255,255,0.12); color:#f8fafc; font-size:12px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase;">
                                {{ $eyebrow }}
                            </div>
                            <h1 style="margin:18px 0 12px; font-family:'Syne', Arial, sans-serif; font-size:34px; line-height:1.05; color:#ffffff;">
                                {{ $subjectLine }}
                            </h1>
                            <p style="margin:0; color:#dbeafe; font-size:16px; line-height:1.7;">
                                {{ $summary }}
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px 10px; background:#08111f;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#ffffff; border-radius:22px; border:1px solid #dbe5f0;">
                                <tr>
                                    <td style="padding:22px;">
                                        <div style="font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#1e98d7; margin-bottom:14px;">
                                            Client overview
                                        </div>
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td style="padding:0 0 12px; font-size:14px; color:#64748b;">Client name</td>
                                                <td style="padding:0 0 12px; font-size:15px; font-weight:700; color:#0f172a; text-align:right;">{{ $client->user->name }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 0 12px; font-size:14px; color:#64748b;">Email</td>
                                                <td style="padding:0 0 12px; font-size:15px; font-weight:700; color:#0f172a; text-align:right;">{{ $client->user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 0 12px; font-size:14px; color:#64748b;">Phone</td>
                                                <td style="padding:0 0 12px; font-size:15px; font-weight:700; color:#0f172a; text-align:right;">
                                                    {{ trim(($client->user->phone_country_code ?? '').' '.($client->user->phone_number ?? '')) ?: 'Not provided' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0; font-size:14px; color:#64748b;">Workflow status</td>
                                                <td style="padding:0; font-size:15px; font-weight:700; color:#0f172a; text-align:right;">{{ $client->onboarding_status ?: 'Not set' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @if (! empty($details))
                        <tr>
                            <td style="padding:18px 32px 10px; background:#08111f;">
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="font-size:13px; font-weight:700; letter-spacing:0.14em; text-transform:uppercase; color:#93c5fd; padding-bottom:12px;">
                                            Operational details
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                                @foreach ($details as $detail)
                                                    <tr>
                                                        <td style="padding:0 8px 14px 0; width:50%;">
                                                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#0f1b2f; border:1px solid rgba(148,163,184,0.16); border-radius:18px;">
                                                                <tr>
                                                                    <td style="padding:16px 18px;">
                                                                        <div style="font-size:12px; font-weight:700; letter-spacing:0.12em; text-transform:uppercase; color:#94a3b8; margin-bottom:8px;">
                                                                            {{ $detail['label'] }}
                                                                        </div>
                                                                        <div style="font-size:16px; font-weight:700; color:#f8fafc; line-height:1.5;">
                                                                            {{ $detail['value'] }}
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style="padding:24px 32px 32px; background:#08111f;">
                            <table role="presentation" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td style="border-radius:999px; background:#7ac70c;">
                                        <a href="{{ route('admin.clients.show', $client) }}" style="display:inline-block; padding:14px 22px; font-size:14px; font-weight:700; color:#08110c; text-decoration:none;">
                                            Review client details
                                        </a>
                                    </td>
                                    <td style="width:12px;"></td>
                                    <td style="border-radius:999px; background:#eff6ff; border:1px solid #bfdbfe;">
                                        <a href="{{ route('dashboard') }}" style="display:inline-block; padding:14px 22px; font-size:14px; font-weight:700; color:#1e40af; text-decoration:none;">
                                            Open dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            <p style="margin:18px 0 0; color:#94a3b8; font-size:13px; line-height:1.7;">
                                This notice was generated by RIF Media to help the admin team handle setup, payment review, and client follow-up without delay.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
