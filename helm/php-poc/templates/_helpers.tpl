{{- define "php-poc.fullname" -}}
{{- printf "%s-%s" .Release.Name "php-poc" | trunc 63 | trimSuffix "-" -}}
{{- end -}}

{{- define "php-poc.labels" -}}
app.kubernetes.io/name: {{ include "php-poc.fullname" . }}
app.kubernetes.io/instance: {{ .Release.Name }}
app.kubernetes.io/version: {{ .Chart.AppVersion }}
app.kubernetes.io/managed-by: Helm
{{- end -}}
