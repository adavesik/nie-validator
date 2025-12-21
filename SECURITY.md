# Security Policy

## Supported Versions

We release patches for security vulnerabilities for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.x     | :white_check_mark: |

## Reporting a Vulnerability

**Please do not report security vulnerabilities through public GitHub issues.**

If you discover a security vulnerability, please send an email to the maintainers. You should receive a response within 48 hours.

Please include the following information:

- Type of vulnerability
- Full paths of source file(s) related to the vulnerability
- Location of the affected source code (tag/branch/commit or direct URL)
- Step-by-step instructions to reproduce the issue
- Proof-of-concept or exploit code (if possible)
- Impact of the vulnerability

## Security Considerations

### Input Validation

This package validates NIE numbers according to the official Spanish algorithm. The validation includes:

- **Type checking**: Only string values are accepted
- **Format validation**: Strict regex pattern matching
- **Checksum verification**: Mathematical validation of the control letter
- **Input normalization**: Automatic sanitization of spaces and hyphens

### No External Dependencies

The core validation logic has minimal dependencies, reducing the attack surface:

- No network requests
- No file system access
- No database queries
- No external API calls

### Safe Defaults

- All input is treated as untrusted
- Strict type declarations prevent type confusion
- No use of `eval()` or dynamic code execution
- No reflection or dynamic class loading

### Rate Limiting

While this package doesn't implement rate limiting directly, we recommend:

- Implementing rate limiting at the application level
- Using Laravel's built-in throttling for form submissions
- Monitoring for unusual validation patterns

## Best Practices

When using this package:

1. **Always validate on the server side**: Never rely solely on client-side validation
2. **Use HTTPS**: Transmit NIE numbers over encrypted connections only
3. **Limit exposure**: Only collect NIE numbers when absolutely necessary
4. **Secure storage**: If storing NIE numbers, encrypt them at rest
5. **Access control**: Restrict access to NIE data to authorized personnel only
6. **Audit logging**: Log access to NIE validation for security auditing
7. **GDPR compliance**: NIE numbers are personal data under GDPR

## Disclosure Policy

When we receive a security report, we will:

1. Confirm the vulnerability
2. Determine the severity and impact
3. Develop and test a fix
4. Release a security update
5. Publicly disclose the vulnerability after the fix is available

## Security Updates

Security updates will be released as patch versions (e.g., 1.0.1) and will be clearly marked in the CHANGELOG.

## Contact

For security concerns, please contact the maintainers directly rather than opening a public issue.
