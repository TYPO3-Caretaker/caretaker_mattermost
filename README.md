# TYPO3 Extension caretaker_mattermost

This extension adds a new exit point to the caretaker.

## Installation

Simply install the extension with Composer or the Extension Manager.

Make sure the option `Advanced notifications` in the caretaker Extension Configuration is enabled.

## Usage

1. Go to your `caretaker-sysfolder` and switch to list module

2. Add a new record of type caretaker `Exitpoint`
    - Add an identifier (e.g. **mattermost**)
    - Add a name (e.g. **Mattermost**)
    - Change the service to **Mattermost**
    - Enter your configuration, at least the **endpoint url** for your Incoming Webhook is required (see 
      https://docs.mattermost.com/developer/webhooks-incoming.html)

3. Add or extend a record of type caretaker `Strategy`
    - see EXT:caretaker/Classes/services/notifications/advanced/demoConfig.txt for more information
```
rules {
    notifyChannels {
        conditions {
            event = updatedTestResult
            onlyIfStateChanged = 1
        }

        exit {
            mattermost {
            }
        }
    }
}
```

4. Assign the `Strategy` to a caretaker `Instance` or `Instancegroup`

## Channels

Channels can be defined in the `Exitpoint` configuration as well as in `Instance` and/or `Instancegroup` properties.

Channels set in `Instance` or `Instancegroup` are handed down. If you need to mute (remove) a channel again, 
you can **add a dash** before the channel name **to remove it** from the list. 
