# blacklisted

A Helm chart for deploying a simple PHP application with PostgreSQL master–replica replication, designed for Minikube.

## Features

* Deploys a PHP app that:
    * It responds to the URL like 'http://host/?n=x' and returns n*n. (it also
    checks whether user is blacklisted or not)
    * It responds to the URL 'http://host/blacklisted' with conditions:
        * Return error code 444 to the visitor
        * Block the IP of the visitor
        * Insert into PostgreSQL table information: path, IP address of the
        visitor and datetime when he got blocked
* PostgreSQL with primary/replica (read-only) setup via Bitnami chart
* NodePort service for local access via browser

---

## Usage

You can use:

```bash
minikube service php
```

Or manually:

1. Get Minikube IP:

```bash
minikube ip
```

2. Get NodePort:

```bash
kubectl get svc php
```

3. Open in browser:

```
http://<minikube-ip>:<node-port>/?n=5
```
