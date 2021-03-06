Behat Concurrency
===============================

**Work in progress, stay tuned!**

Behat extension which brings concurrency!

The most common way to speedup Behat suites is to run them in separate worker processes. Usually users splits their suites into list of scenarios to execute using filters (manually or automatically) and then run each filtered list of scenario in separate Behat instance. This is pretty easy to do but this approach have it's own disadvantages:

 - **Fixed list of scenarios per worker**, which means that when you run your suite with two workers, they will have pretty match the same ammount of scenarios to execute, but some scenarios can be executed slower and some of them can be executed much faster. This causes uneven load on workers and does not allow to utilize
 - **impossible to parallelize the execution of outlines scenarios**. For example you have 9 scenarios and 1 outline scenario with 100 examples (hardly you will do something like this, but). In case if we start 2 worker processes, each will execute 5 difference scenarios, and one of the worker will preform outline scenario without any speed boost.

For those reasons I've made

## How it works

The difference between approach described above and this extension is dynamic scheduling. When you run behat with `--concurrently` option, this will:

 - start scheduler, which will build queue of scenarios to execute.
 - create pool of worker processes
 - connect scheduler and workers via TCP or Unix sockets
 - will give each free worker another task in loop (with non-blocking I/O)

Scheduler will send tasks to workers and will wait for result. When one of workers is done, it sends back test execution results, which mean that it ready for another job. If one of workers will fails, then process manager will replace it with fresh one and mark scenario as failed (if you didn't pass `--stop-on-failure` option). In case of outline scenario, each example will be executed as independent scenario, which allow us to execute then in parallel.

If you are using E2E approach in your step implementation, then you probably should make it possible to run each task independently. To do so each worker will has `BEHAT_WORKER_NUMBER` env variable, which will allow us to use separate database connection per worker for example.

## Limitations

 - `beforeSuite` and `afterSuite` events are not available
