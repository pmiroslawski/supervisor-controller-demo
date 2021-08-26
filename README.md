# supervisor-controller-demo

Simple PHP application to manage supervisors processes

Application is built on the top of [pmiroslawski/supervisor-controller-bundle](https://github.com/pmiroslawski/supervisor-controller-bundle).

The main purpose of that application is to monitor RabbitMQ queue and start extra consumers if number of messages is increasing above specified level (when current number of consumers are not able to consuming all messages). If number of messages is decreasing then according to configured thresholds number of consumers will be decreased as well.


## Installation

Clone this reposiotory and copy a `.env` file to `.env.local` then:

1.Update settings in .env.local file:

    APP_ENV=dev
    APP_SECRET=XX
    SUPERVISOR_HOST=127.0.0.1
    SUPERVISOR_PORT=9001
    RABBITMQ_HTTP_API_HOST=http://root:root@127.0.0.1:15672/api/queues/%2F
    TELEGRAM_DSN=telegram://TOKEN@default?channel=CHAT_ID

2. Update queues configuration and set proper thresholds for them in `config\packages\bit9_supervisor_controller.yaml`
    ```yaml
        bit9_supervisor_controller:
            queues:
               - name: messages
                 consumer: message_consumer
                 numprocs: 50               # run 50 if more than 10000
                 thresholds:
                    - messages: 100         # run 3 processes if less than 100 elements in queue 
                      num: 3
                    - messages: 1000        # run 5 processes if less than 1000 elements in queue 
                      num: 5
                    - messages: 10000       # run 10 processes if less than 10000 elements in queue 
                      num: 10
    ```

3. Clear application cache
    ```
        ./bin/console cache:clear
    ```

4. Add to the cron below command:
    ```
        ./bin/console supervisor:queues:watchdog
    ```

## Usage
Check [pmiroslawski/supervisor-controller-bundle](https://github.com/pmiroslawski/supervisor-controller-bundle) to see more.
