SpamAssassin API
==============

## CLI
### Clients management
| Command                      | Description |
| ---------------------------- | ------------|
| `client:create`              | Creates client. |                     
| `client:disable`             | Disables client. |                   
| `client:regenerate-token`    | Regenerates client's token. |
| `client:set-allowed-actions` | Updates client's allowed actions. |

Example:
`docker-compose exec web bin/console client:set-allowed-actions emil --allowed-action=read --allowed-action=write`
