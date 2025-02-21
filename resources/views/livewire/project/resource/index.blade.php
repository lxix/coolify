<div>
    <div class="flex flex-col">
        <div class="flex items-center gap-2">
            <h1>Resources</h1>
            @if ($environment->isEmpty())
                <a class="font-normal text-white normal-case border-none rounded hover:no-underline btn btn-primary btn-sm no-animation"
                    href="{{ route('project.clone-me', ['project_uuid' => data_get($project, 'uuid'), 'environment_name' => request()->route('environment_name')]) }}">
                    Clone
                </a>
            @else
                <a href="{{ route('project.resource.create', ['project_uuid' => request()->route('project_uuid'), 'environment_name' => request()->route('environment_name')]) }}  "
                    class="font-normal text-white normal-case border-none rounded hover:no-underline btn btn-primary btn-sm no-animation">+
                    New</a>
                <a class="font-normal text-white normal-case border-none rounded hover:no-underline btn btn-primary btn-sm no-animation"
                    href="{{ route('project.clone-me', ['project_uuid' => data_get($project, 'uuid'), 'environment_name' => request()->route('environment_name')]) }}">
                    Clone
                </a>
            @endif
            <livewire:project.delete-environment :disabled="!$environment->isEmpty()" :environment_id="$environment->id" />
        </div>
        <nav class="flex pt-2 pb-10">
            <ol class="flex items-center">
                <li class="inline-flex items-center">
                    <a class="text-xs truncate lg:text-sm"
                        href="{{ route('project.show', ['project_uuid' => request()->route('project_uuid')]) }}">
                        {{ $project->name }}</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg aria-hidden="true" class="w-4 h-4 mx-1 font-bold text-warning" fill="currentColor"
                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <a class="text-xs truncate lg:text-sm"
                            href="{{ route('project.resource.index', ['environment_name' => request()->route('environment_name'), 'project_uuid' => request()->route('project_uuid')]) }}">{{ request()->route('environment_name') }}</a>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    @if ($environment->isEmpty())
        <a href="{{ route('project.resource.create', ['project_uuid' => request()->route('project_uuid'), 'environment_name' => request()->route('environment_name')]) }}  "
            class="items-center justify-center box">+ Add New Resource</a>
    @else
        <div x-data="searchComponent()">
            <x-forms.input autofocus="true" placeholder="Search for name, fqdn..." class="w-full" x-model="search" />
            <div class="grid grid-cols-1 gap-4 pt-4 lg:grid-cols-2 xl:grid-cols-3">
                <template x-for="item in filteredApplications" :key="item.id">
                    <span>
                        <a class="h-24 box group" :href="item.hrefLink">
                            <div class="flex flex-col w-full px-4 mx-2">
                                <div class="flex gap-2">
                                    <div class="pb-2 font-bold text-white" x-text="item.name"></div>
                                    <template x-if="item.status.startsWith('running')">
                                        <div title="running" class="mt-1 bg-success badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('exited')">
                                        <div title="exited" class="mt-1 bg-error badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('restarting')">
                                        <div title="restarting" class="mt-1 bg-warningbadge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('degraded')">
                                        <div title="degraded" class="mt-1 bg-warning badge badge-xs"></div>
                                    </template>
                                </div>
                                <div class="max-w-full truncate description" x-text="item.description"></div>
                                <div class="max-w-full truncate description" x-text="item.fqdn"></div>
                            </div>
                        </a>
                        <div class="flex gap-1 pt-1 group-hover:text-white group min-h-6">
                            <template x-for="tag in item.tags">
                                <div class="px-2 py-1 cursor-pointer description bg-coolgray-100 hover:bg-coolgray-300"
                                    @click.prevent="gotoTag(tag.name)" x-text="tag.name"></div>
                            </template>
                            <div class="flex items-center px-2 text-xs cursor-pointer text-neutral-500/20 group-hover:text-white hover:bg-coolgray-300"
                                @click.prevent="goto(item)">Add tag</div>
                        </div>
                    </span>
                </template>
                <template x-for="item in filteredPostgresqls" :key="item.id">
                    <span>
                        <a class="h-24 box group" :href="item.hrefLink">
                            <div class="flex flex-col px-4 mx-2">
                                <div class="flex gap-2">
                                    <div class="pb-2 font-bold text-white" x-text="item.name"></div>
                                    <template x-if="item.status.startsWith('running')">
                                        <div title="running" class="mt-1 bg-success badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('exited')">
                                        <div title="exited" class="mt-1 bg-error badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('restarting')">
                                        <div title="restarting" class="mt-1 bg-warningbadge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('degraded')">
                                        <div title="degraded" class="mt-1 bg-warning badge badge-xs"></div>
                                    </template>
                                </div>
                                <div class="max-w-full truncate description" x-text="item.description"></div>
                            </div>
                        </a>
                        <div class="flex gap-1 pt-1 group-hover:text-white group min-h-6">
                            <template x-for="tag in item.tags">
                                <div class="px-2 py-1 cursor-pointer description bg-coolgray-100 hover:bg-coolgray-300"
                                    @click.prevent="gotoTag(tag.name)" x-text="tag.name"></div>
                            </template>
                            <div class="flex items-center px-2 text-xs cursor-pointer text-neutral-500/20 group-hover:text-white hover:bg-coolgray-300"
                                @click.prevent="goto(item)">Add tag</div>
                        </div>
                    </span>
                </template>
                <template x-for="item in filteredRedis" :key="item.id">
                    <span>
                        <a class="h-24 box group" :href="item.hrefLink">
                            <div class="flex flex-col px-4 mx-2">
                                <div class="flex gap-2">
                                    <div class="pb-2 font-bold text-white" x-text="item.name"></div>
                                    <template x-if="item.status.startsWith('running')">
                                        <div title="running" class="mt-1 bg-success badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('exited')">
                                        <div title="exited" class="mt-1 bg-error badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('restarting')">
                                        <div title="restarting" class="mt-1 bg-warningbadge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('degraded')">
                                        <div title="degraded" class="mt-1 bg-warning badge badge-xs"></div>
                                    </template>
                                </div>
                                <div class="max-w-full truncate description" x-text="item.description"></div>
                            </div>
                        </a>
                        <div class="flex gap-1 pt-1 group-hover:text-white group min-h-6">
                            <template x-for="tag in item.tags">
                                <div class="px-2 py-1 cursor-pointer description bg-coolgray-100 hover:bg-coolgray-300"
                                    @click.prevent="gotoTag(tag.name)" x-text="tag.name"></div>
                            </template>
                            <div class="flex items-center px-2 text-xs cursor-pointer text-neutral-500/20 group-hover:text-white hover:bg-coolgray-300"
                                @click.prevent="goto(item)">Add tag</div>
                        </div>
                    </span>
                </template>
                <template x-for="item in filteredMongodbs" :key="item.id">
                    <span>
                        <a class="h-24 box group" :href="item.hrefLink">
                            <div class="flex flex-col px-4 mx-2">
                                <div class="flex gap-2">
                                    <div class="pb-2 font-bold text-white" x-text="item.name"></div>
                                    <template x-if="item.status.startsWith('running')">
                                        <div title="running" class="mt-1 bg-success badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('exited')">
                                        <div title="exited" class="mt-1 bg-error badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('restarting')">
                                        <div title="restarting" class="mt-1 bg-warningbadge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('degraded')">
                                        <div title="degraded" class="mt-1 bg-warning badge badge-xs"></div>
                                    </template>
                                </div>
                                <div class="max-w-full truncate description" x-text="item.description"></div>
                            </div>
                        </a>
                        <div class="flex gap-1 pt-1 group-hover:text-white group min-h-6">
                            <template x-for="tag in item.tags">
                                <div class="px-2 py-1 cursor-pointer description bg-coolgray-100 hover:bg-coolgray-300"
                                    @click.prevent="gotoTag(tag.name)" x-text="tag.name"></div>
                            </template>
                            <div class="flex items-center px-2 text-xs cursor-pointer text-neutral-500/20 group-hover:text-white hover:bg-coolgray-300"
                                @click.prevent="goto(item)">Add tag</div>
                        </div>
                    </span>
                </template>
                <template x-for="item in filteredMysqls" :key="item.id">
                    <span>
                        <a class="h-24 box group" :href="item.hrefLink">
                            <div class="flex flex-col px-4 mx-2">
                                <div class="flex gap-2">
                                    <div class="pb-2 font-bold text-white" x-text="item.name"></div>
                                    <template x-if="item.status.startsWith('running')">
                                        <div title="running" class="mt-1 bg-success badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('exited')">
                                        <div title="exited" class="mt-1 bg-error badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('restarting')">
                                        <div title="restarting" class="mt-1 bg-warningbadge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('degraded')">
                                        <div title="degraded" class="mt-1 bg-warning badge badge-xs"></div>
                                    </template>
                                </div>
                                <div class="max-w-full truncate description" x-text="item.description"></div>
                            </div>
                        </a>
                        <div class="flex gap-1 pt-1 group-hover:text-white group min-h-6">
                            <template x-for="tag in item.tags">
                                <div class="px-2 py-1 cursor-pointer description bg-coolgray-100 hover:bg-coolgray-300"
                                    @click.prevent="gotoTag(tag.name)" x-text="tag.name"></div>
                            </template>
                            <div class="flex items-center px-2 text-xs cursor-pointer text-neutral-500/20 group-hover:text-white hover:bg-coolgray-300"
                                @click.prevent="goto(item)">Add tag</div>
                        </div>
                    </span>
                </template>
                <template x-for="item in filteredMariadbs" :key="item.id">
                    <span>
                        <a class="h-24 box group" :href="item.hrefLink">
                            <div class="flex flex-col px-4 mx-2">
                                <div class="flex gap-2">
                                    <div class="pb-2 font-bold text-white" x-text="item.name"></div>
                                    <template x-if="item.status.startsWith('running')">
                                        <div title="running" class="mt-1 bg-success badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('exited')">
                                        <div title="exited" class="mt-1 bg-error badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('restarting')">
                                        <div title="restarting" class="mt-1 bg-warningbadge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('degraded')">
                                        <div title="degraded" class="mt-1 bg-warning badge badge-xs"></div>
                                    </template>
                                </div>
                                <div class="max-w-full truncate description" x-text="item.description"></div>
                            </div>
                        </a>
                        <div class="flex gap-1 pt-1 group-hover:text-white group min-h-6">
                            <template x-for="tag in item.tags">
                                <div class="px-2 py-1 cursor-pointer description bg-coolgray-100 hover:bg-coolgray-300"
                                    @click.prevent="gotoTag(tag.name)" x-text="tag.name"></div>
                            </template>
                            <div class="flex items-center px-2 text-xs cursor-pointer text-neutral-500/20 group-hover:text-white hover:bg-coolgray-300"
                                @click.prevent="goto(item)">Add tag</div>
                        </div>
                    </span>
                </template>
                <template x-for="item in filteredServices" :key="item.id">
                    <span>
                        <a class="h-24 box group" :href="item.hrefLink">
                            <div class="flex flex-col px-4 mx-2">
                                <div class="flex gap-2">
                                    <div class="pb-2 font-bold text-white" x-text="item.name"></div>
                                    <template x-if="item.status.startsWith('running')">
                                        <div title="running" class="mt-1 bg-success badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('exited')">
                                        <div title="exited" class="mt-1 bg-error badge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('restarting')">
                                        <div title="restarting" class="mt-1 bg-warningbadge badge-xs"></div>
                                    </template>
                                    <template x-if="item.status.startsWith('degraded')">
                                        <div title="degraded" class="mt-1 bg-warning badge badge-xs"></div>
                                    </template>
                                </div>
                                <div class="max-w-full truncate description" x-text="item.description"></div>
                            </div>
                        </a>
                        <div class="flex gap-1 pt-1 group-hover:text-white group min-h-6">
                            <template x-for="tag in item.tags">
                                <div class="px-2 py-1 cursor-pointer description bg-coolgray-100 hover:bg-coolgray-300"
                                    @click.prevent="gotoTag(tag.name)" x-text="tag.name"></div>
                            </template>
                            <div class="flex items-center px-2 text-xs cursor-pointer text-neutral-500/20 group-hover:text-white hover:bg-coolgray-300"
                                @click.prevent="goto(item)">Add tag</div>
                        </div>
                    </span>
                </template>
            </div>
        </div>
    @endif

</div>

<script>
    function sortFn(a, b) {
        return a.name.localeCompare(b.name)
    }

    function searchComponent() {
        return {
            search: '',
            applications: @js($applications),
            postgresqls: @js($postgresqls),
            redis: @js($redis),
            mongodbs: @js($mongodbs),
            mysqls: @js($mysqls),
            mariadbs: @js($mariadbs),
            services: @js($services),
            gotoTag(tag) {
                window.location.href = '/tags/' + tag;
            },
            goto(item) {
                const hrefLink = item.hrefLink;
                window.location.href = `${hrefLink}#tags`;
            },
            get filteredApplications() {
                if (this.search === '') {
                    return Object.values(this.applications).sort(sortFn);
                }
                this.applications = Object.values(this.applications);
                return this.applications.filter(item => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.fqdn?.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.description?.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.tags?.some(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
                }).sort(sortFn);
            },
            get filteredPostgresqls() {
                if (this.search === '') {
                    return Object.values(this.postgresqls).sort(sortFn);
                }
                this.postgresqls = Object.values(this.postgresqls);
                return this.postgresqls.filter(item => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.description?.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.tags?.some(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
                }).sort(sortFn);
            },
            get filteredRedis() {
                if (this.search === '') {
                    return Object.values(this.redis).sort(sortFn);
                }
                this.redis = Object.values(this.redis);
                return this.redis.filter(item => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.description?.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.tags?.some(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
                }).sort(sortFn);
            },
            get filteredMongodbs() {
                if (this.search === '') {
                    return Object.values(this.mongodbs).sort(sortFn);
                }
                this.mongodbs = Object.values(this.mongodbs);
                return this.mongodbs.filter(item => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.description?.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.tags?.some(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
                }).sort(sortFn);
            },
            get filteredMysqls() {
                if (this.search === '') {
                    return Object.values(this.mysqls).sort(sortFn);
                }
                this.mysqls = Object.values(this.mysqls);
                return this.mysqls.filter(item => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.description?.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.tags?.some(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
                }).sort(sortFn);
            },
            get filteredMariadbs() {
                if (this.search === '') {
                    return Object.values(this.mariadbs).sort(sortFn);
                }
                this.mariadbs = Object.values(this.mariadbs);
                return this.mariadbs.filter(item => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.description?.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.tags?.some(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
                }).sort(sortFn);
            },
            get filteredServices() {
                if (this.search === '') {
                    return Object.values(this.services).sort(sortFn);
                }
                this.services = Object.values(this.services);
                return this.services.filter(item => {
                    return item.name.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.description?.toLowerCase().includes(this.search.toLowerCase()) ||
                        item.tags?.some(tag => tag.name.toLowerCase().includes(this.search.toLowerCase()));
                }).sort(sortFn);
            },

        };
    }
</script>
