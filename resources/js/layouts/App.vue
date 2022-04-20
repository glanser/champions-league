<template>
    <div class="container">
        <div>
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <a class="navbar-brand ms-3" href="#">Champions League</a>
            </nav>

            <div class="container">
                <div class="row">
                    <div class="col-12 mt-2">
                        <h2>Teams: </h2>
                        <div v-if="teams" class="">
                            <table class="table table-borderless table-striped">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Pl</th>
                                    <th>W</th>
                                    <th>D</th>
                                    <th>L</th>
                                    <th>GD</th>
                                    <th>Pts</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="team in teams">
                                    <td>{{ team.id }}</td>
                                    <td>{{ team.name }}</td>
                                    <td>{{ team.played }}</td>
                                    <td>{{ team.won }}</td>
                                    <td>{{ team.draw }}</td>
                                    <td>{{ team.lost }}</td>
                                    <td>{{ team.goal_difference }}</td>
                                    <td>{{ team.points }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row" v-if="fixtures">
                    <div class="col-4" v-for="week in fixtures">
                        <h3>Week {{ week[0].week }}</h3>
                        <table class="table table-borderless table-striped">
                            <tbody>
                            <tr v-for="fixture in week">
                                <td class="col-5">{{ fixture.first_team.name }}</td>
                                <td class="fw-bold text-center col-2">{{ fixture.first_team_goals }} - {{ fixture.second_team_goals }}</td>
                                <td class="text-end col-5">{{ fixture.second_team.name }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" v-else>
                    <div class="col-12">
                        <button class="btn btn-primary" v-on:click="generateFixtures">Generate Fixtures</button>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6" v-if="results">
                        <h3>Results:</h3>
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <th>Team</th>
                                <th>Pl</th>
                                <th>W</th>
                                <th>D</th>
                                <th>L</th>
                                <th>GF</th>
                                <th>GA</th>
                                <th>GD</th>
                                <th>Pts</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="teamResult in results">
                                <td>{{ teamResult.team_name }}</td>
                                <td>{{ teamResult.played }}</td>
                                <td>{{ teamResult.won }}</td>
                                <td>{{ teamResult.draw }}</td>
                                <td>{{ teamResult.lost }}</td>
                                <td>{{ teamResult.goals_for }}</td>
                                <td>{{ teamResult.goals_against }}</td>
                                <td>{{ teamResult.goals_difference }}</td>
                                <td>{{ teamResult.points }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-6" v-if="predictions">
                        <h3>Predictions:</h3>
                        <table class="table table-borderless table-striped">
                            <thead>
                            <tr>
                                <th>Team</th>
                                <th>Pl</th>
                                <th>W</th>
                                <th>D</th>
                                <th>L</th>
                                <th>GF</th>
                                <th>GA</th>
                                <th>GD</th>
                                <th>Pts</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="teamPredictions in predictions">
                                <td>{{ teamPredictions.team_name }}</td>
                                <td>{{ teamPredictions.played }}</td>
                                <td>{{ teamPredictions.won }}</td>
                                <td>{{ teamPredictions.draw }}</td>
                                <td>{{ teamPredictions.lost }}</td>
                                <td>{{ teamPredictions.goals_for }}</td>
                                <td>{{ teamPredictions.goals_against }}</td>
                                <td>{{ teamPredictions.goals_difference }}</td>
                                <td>{{ teamPredictions.points }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row" v-if="fixtures">
                    <hr>
                    <div class="col-6">

                        <button class="btn btn-primary" v-on:click="playAllWeeks">Play All Weeks</button>
                        <button class="btn btn-primary" v-on:click="playNextWeek">Play Next Week</button>
                        <span class="alert alert-danger" v-if="error">{{ error }}</span>
                    </div>
                    <div class="col-6 text-end">
                        <button class="btn btn-danger" v-on:click="reset">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import _ from 'lodash';

export default {
    data() {
        return {
            teams: null,
            fixtures: null,
            results: null,
            predictions: null,
            error: null,
        }
    },
    mounted() {
        this.initData()
    },
    methods: {
        initData() {
            this.$http.get('/api/v1/teams').then(response => this.teams = response.data.data);
            this.$http.get('/api/v1/fixtures').then(response => {
                this.fixtures = !_.isEmpty(response.data.data)
                    ? this.fixtures = _.groupBy(response.data.data, fixture => fixture.week)
                    : null;
            });
            this.$http.get('/api/v1/results').then(response => {
                this.results = !_.isEmpty(response.data.data)
                    ? _.orderBy(response.data.data, result => result.points, 'desc')
                    : null;
            });
            this.$http.get('/api/v1/predictions').then(response => {
                this.predictions = !_.isEmpty(response.data.data)
                    ? _.orderBy(response.data.data, result => result.points, 'desc')
                    : null;
            });
        },
        async generateFixtures() {
            let response = await this.$http.post('/api/v1/fixtures/generate');
            this.fixtures = response.data.data;
            this.initData();
        },
        async reset() {
            let response = await this.$http.post('/api/v1/fixtures/reset');
            this.initData();
            this.error = null;
        },
        async playNextWeek() {
            try {
                await this.$http.post('/api/v1/fixtures/play-next');
            } catch (e) {
                console.log(e.response);
                this.error = e.response.data.message
            }
            this.initData();
        },
        async playAllWeeks() {
            let response = await this.$http.post('/api/v1/fixtures/play-all');
            this.initData();
        }
    }
}
;
</script>
