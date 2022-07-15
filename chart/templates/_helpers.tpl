{{- define "blacklisted.fullname" -}}
{{ .Release.Name }}
{{- end }}

{{- define "blacklisted.primary.fullname" -}}
{{ .Release.Name }}-postgresql-primary
{{- end }}

{{- define "blacklisted.read.fullname" -}}
{{ .Release.Name }}-postgresql-read
{{- end }}

