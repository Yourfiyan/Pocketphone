# GitHub Actions Workflow Security Guidelines

This document provides security guidelines for creating and maintaining GitHub Actions workflows in the Pocketphone repository.

---

## 🔒 Core Security Principles

### 1. Never Hardcode Secrets in Workflows

**❌ NEVER DO THIS:**
```yaml
name: Deploy
on: [push]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to server
        run: |
          scp -i "ssh-key-here" ./app user@server.com:/var/www
        env:
          API_KEY: "sk-1234567890abcdef"
          DB_PASSWORD: "mypassword123"
```

**✅ ALWAYS DO THIS:**
```yaml
name: Deploy
on: [push]
jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Deploy to server
        env:
          API_KEY: ${{ secrets.API_KEY }}
          DB_PASSWORD: ${{ secrets.DB_PASSWORD }}
        run: |
          # Your deployment script
```

---

## 🔑 Managing Secrets

### Adding Secrets to GitHub

1. Navigate to your repository on GitHub
2. Go to **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add your secret name and value
5. Click **Add secret**

### Secret Naming Conventions
- Use UPPERCASE with underscores: `API_KEY`, `DB_PASSWORD`
- Be descriptive: `PROD_DB_PASSWORD` vs `DEV_DB_PASSWORD`
- Group related secrets: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`

### Types of Secrets to Store
- API keys and tokens
- Database credentials
- SSH keys
- OAuth tokens
- Encryption keys
- Service account credentials

---

## 🛡️ Workflow Permission Best Practices

### Principle of Least Privilege

Always specify the minimum permissions required:

```yaml
name: Build and Test
on: [push, pull_request]

permissions:
  contents: read        # Read repository contents
  pull-requests: write  # Comment on PRs

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - name: Run tests
        run: npm test
```

### Default Permissions to Avoid

Don't use overly permissive defaults:

```yaml
# ❌ Avoid this unless necessary
permissions: write-all

# ✅ Be specific
permissions:
  contents: read
  issues: write
  pull-requests: write
```

---

## 🔐 Preventing Secret Leakage

### 1. Never Echo or Print Secrets

**❌ DANGEROUS:**
```yaml
- name: Debug
  run: |
    echo "API Key: ${{ secrets.API_KEY }}"
    echo "Password: ${{ secrets.DB_PASSWORD }}"
```

**✅ SAFE:**
```yaml
- name: Use Secret Safely
  run: |
    # Secrets are automatically masked in logs
    curl -H "Authorization: Bearer ${{ secrets.API_KEY }}" https://api.example.com
```

### 2. Be Careful with PR from Forks

```yaml
name: PR Check
on:
  pull_request_target:  # ⚠️ DANGEROUS - has access to secrets
    
# Better approach:
on:
  pull_request:  # ✅ SAFER - no secret access for forks
```

### 3. Review Logs Before Making Public

- GitHub automatically masks registered secrets in logs
- But be aware of:
  - Base64 encoded secrets
  - URL-encoded secrets
  - Secrets split across multiple echo statements

---

## 🌐 OIDC Instead of Long-Lived Credentials

For cloud providers (AWS, Azure, GCP), use OpenID Connect:

### Example: AWS with OIDC

```yaml
name: Deploy to AWS
on: [push]

permissions:
  id-token: write   # Required for OIDC
  contents: read

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - name: Configure AWS Credentials
        uses: aws-actions/configure-aws-credentials@v4
        with:
          role-to-assume: arn:aws:iam::123456789012:role/GitHubActionsRole
          aws-region: us-east-1
      
      - name: Deploy
        run: |
          aws s3 sync ./dist s3://my-bucket
```

**Benefits:**
- No long-lived credentials to manage
- Automatic credential rotation
- Better audit trail
- Reduced risk of credential leakage

---

## 🔍 Third-Party Actions Security

### Verify Actions Before Using

1. **Pin to specific SHA (most secure):**
```yaml
- uses: actions/checkout@8e5e7e5ab8b370d6c329ec480221332ada57f0ab  # v4.1.1
```

2. **Pin to version tag (good):**
```yaml
- uses: actions/checkout@v4
```

3. **Never use branch references in production:**
```yaml
- uses: actions/checkout@main  # ❌ DANGEROUS - can change at any time
```

### Audit Third-Party Actions
- Review the action's source code
- Check GitHub star count and usage
- Look for verified creator badge
- Read recent issues and security advisories
- Use only well-maintained actions

---

## 🏗️ Environment Protection

### For Production Deployments

1. Create environment in **Settings** → **Environments**
2. Set protection rules:
   - Required reviewers
   - Wait timer
   - Deployment branches

```yaml
name: Production Deploy
on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    environment: 
      name: production
      url: https://yourfiyan.qzz.io
    steps:
      - name: Deploy
        run: echo "Deploying to production"
```

---

## 📋 Security Checklist for New Workflows

Before committing a new workflow, verify:

- [ ] No hardcoded secrets or credentials
- [ ] All sensitive values use `${{ secrets.SECRET_NAME }}`
- [ ] Minimum permissions specified
- [ ] Third-party actions pinned to specific versions
- [ ] No debug statements that print secrets
- [ ] Appropriate environment protection for production
- [ ] No use of `pull_request_target` unless necessary
- [ ] OIDC used for cloud providers (where applicable)
- [ ] Workflow tested in a safe environment first

---

## 🚨 Common Vulnerabilities to Avoid

### 1. Script Injection

**❌ VULNERABLE:**
```yaml
- name: Greet
  run: echo "Hello ${{ github.event.issue.title }}"
```

**✅ SAFE:**
```yaml
- name: Greet
  env:
    TITLE: ${{ github.event.issue.title }}
  run: echo "Hello $TITLE"
```

### 2. Untrusted Input in Actions

**❌ DANGEROUS:**
```yaml
- name: Run command
  run: ${{ github.event.comment.body }}
```

**✅ SAFE:**
```yaml
- name: Run command
  if: github.event.comment.body == '/deploy'
  run: ./deploy.sh
```

### 3. Exposing Secrets in Artifacts

**❌ DANGEROUS:**
```yaml
- name: Upload config
  uses: actions/upload-artifact@v3
  with:
    name: config
    path: config.json  # Contains secrets!
```

**✅ SAFE:**
```yaml
- name: Upload sanitized config
  uses: actions/upload-artifact@v3
  with:
    name: config
    path: config-public.json  # Secrets removed
```

---

## 📚 Additional Resources

- [GitHub Actions Security Best Practices](https://docs.github.com/en/actions/security-guides/security-hardening-for-github-actions)
- [Encrypted Secrets Documentation](https://docs.github.com/en/actions/security-guides/encrypted-secrets)
- [OpenID Connect in GitHub Actions](https://docs.github.com/en/actions/deployment/security-hardening-your-deployments/about-security-hardening-with-openid-connect)
- [Security hardening for GitHub Actions](https://docs.github.com/en/actions/security-guides/security-hardening-for-github-actions)

---

## 📞 Support

If you discover a security vulnerability in a workflow:
1. **Do NOT** create a public issue
2. Contact the repository maintainer directly
3. Or use GitHub's private vulnerability reporting feature

---

**Last Updated:** 2025-11-24  
**Document Version:** 1.0  
**Maintainer:** [@Yourfiyan](https://github.com/Yourfiyan)
