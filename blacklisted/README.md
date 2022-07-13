# Chart for PostgreSQL with PHP application on ingress

A Kubernetes Helm Chart for app deployment on minikube.

## Installing the Chart

Write a values.yml file:

```
auth:
  username: 'root'
  password: 'root'
  database: 'blacklistdb'
  replicationUsername: 'repl_user'
  replicationPassword: 'repl_password'

primary:
  initdb:
    scripts:
      table.sql: |
          CREATE TABLE blacklisted (
              ip CHAR(15),
              date_ip DATE,
              PRIMARY KEY (ip)
          );
    user: 'root'
    password: 'root'

architecture: replication

readReplicas:
  replicaCount: 1
```

Then execute:

``` shell
minikube addons enable ingress
helm install blacklisted blacklisted -f values.yaml
```

It uses 'host' for host and because of that please get your ingress ip address and add the following line to your ```/etc/hosts``` file:

```
{{ ingress IP address here }}  host
```

## Uninstalling the Chart

To uninstall/delete the blacklisted deployment:

```console
helm delete blacklisted
```

The command removes all the Kubernetes components but PVC's associated with the chart and deletes the release.
