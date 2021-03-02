<template>
  <div id="right-body">
    <h1>Development CRM</h1>

    <div class="horizontal"></div>

    <p>
      Lorem Ipsum adalah contoh teks atau dummy dalam industri percetakan dan
      penataan huruf atau typesetting. Lorem Ipsum telah menjadi standar contoh
      teks sejak tahun 1500an, saat seorang tukang cetak yang tidak dikenal
      mengambil sebuah kumpulan teks dan mengacaknya untuk menjadi sebuah buku
      contoh huruf.
    </p>

    <div class="today">
      <div class="add-task">
        <h2>Today's Task</h2>

        <div class="btn-action">
          <button type="button" class="btn btn-round btn-primary btn-sm">
            <i class="fa fa-plus"></i>
          </button>
        </div>
      </div>

      <ul class="task-list">
        <li v-for="todaytask in today" v-bind:key="todaytask.id">
          <div class="info">
            <div class="left">
              <input
                type="checkbox"
                class="myCheckbox"
                name="test"
                :checked="todaytask.completed"
                @change="checkToday(todaytask.taskid)"
              />

              <h4>{{ todaytask.title }}</h4>
            </div>

            <div class="right">
              <button type="button" class="btn btn-round btn-primary btn-sm">
                <i class="fa fa-edit"></i>
              </button>
              <button
                type="button"
                class="btn btn-round btn-danger btn-sm"
                @click="delToday(todaytask.taskid)"
              >
                <i class="fa fa-trash"></i>
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>

    <div class="upcoming">
      <div class="add-task">
        <h2>Upcoming's Task</h2>

        <div class="btn-action">
          <button type="button" class="btn btn-round btn-primary btn-sm">
            <i class="fa fa-plus"></i>
          </button>
        </div>
      </div>

      <form action="" @submit="addUpcomingTask">
        <input type="text" placeholder="add new task.." v-model="newTask" />
      </form>

      <ul class="task-list">
        <li v-for="upcomingtask in upcoming" v-bind:key="upcomingtask.id">
          <div class="info">
            <div class="left">
              <input
                type="checkbox"
                class="myCheckbox"
                name="test"
                :checked="upcomingtask.completed"
                @change="checkUpcoming(upcomingtask.taskid)"
              />

              <h4>{{ upcomingtask.title }}</h4>
            </div>

            <div class="right">
              <button type="button" class="btn btn-round btn-primary btn-sm">
                <i class="fa fa-edit"></i>
              </button>
              <button
                type="button"
                class="btn btn-round btn-danger btn-sm"
                @click="delUpcoming(upcomingtask.taskid)"
              >
                <i class="fa fa-trash"></i>
              </button>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      today: [],
      upcoming: [],
      newTask: "",
    };
  },

  created() {
    this.fetchTodayTask();
    this.fetchUpcomingTask();
  },

  methods: {
    // Upcoming task
    fetchUpcomingTask() {
      fetch("/api/upcoming/list")
        .then((res) => res.json())
        .then(({ data }) => {
          this.upcoming = data;
        })
        .catch((err) => console.log(err));
    },

    addUpcomingTask(e) {
      e.preventDefault();

      if (this.upcoming.length > 4) {
        alert("Please complete the upcoming task");
      } else {
        const newTask = {
          taskid: Math.random().toString(36).substring(7),
          title: this.newTask,
        };

        fetch("/api/upcoming/add", {
          method: "POST",
          headers: {
            "content-type": "application/json",
          },
          body: JSON.stringify(newTask),
        }).then(() => this.upcoming.push(newTask));

        this.newTask = "";
      }
    },

    delUpcoming(taskid) {
      if (confirm("Anda yakin ingin menghapus task ini?")) {
        fetch("/api/upcoming/delete/" + taskid, {
          method: "DELETE",
        })
          .then((res) => res.json())
          .then((data) => {
            this.upcoming = this.upcoming.filter(
              ({ taskid: id }) => id !== taskid
            );
          });
      }
    },

    checkUpcoming(taskid) {
      if (this.today.length > 4) {
        alert("Sorry complete existing task");
      } else {
        this.addTodayTask(taskid);

        fetch("/api/upcoming/delete/" + taskid, {
          method: "DELETE",
        }).then(() => {
          this.upcoming = this.upcoming.filter(
            ({ taskid: id }) => id !== taskid
          );
        });
      }
    },

    // Today task
    fetchTodayTask() {
      fetch("/api/today/list")
        .then((res) => res.json())
        .then(({ data }) => {
          this.today = data;
        })
        .catch((err) => console.log(err));
    },

    addTodayTask(taskid) {
      const task = this.upcoming.filter(({ taskid: id }) => id == taskid)[0];

      fetch("/api/today/add", {
        method: "POST",
        headers: {
          "content-type": "application/json",
        },
        body: JSON.stringify(task),
      })
        .then(() => this.today.unshift(task))
        .catch((err) => console.log(err));

      this.task = "";
    },

    delToday(taskid) {
      if (confirm("Anda yakin ingin menghapus task ini?")) {
        fetch("/api/today/delete/" + taskid, {
          method: "DELETE",
        })
          .then((res) => res.json())
          .then((data) => {
            this.today = this.today.filter(({ taskid: id }) => id !== taskid);
          });
      }
    },

    checkToday(taskid) {
      if (confirm("Task complete?")) {
        fetch("/api/today/delete/" + taskid, {
          method: "DELETE",
        })
          .then(() => {
            this.today = this.today.filter(({ taskid: id }) => id !== taskid);
          })
          .catch((err) => console.log(err));
      }
    },
  },
};
</script>

<style>
</style>
