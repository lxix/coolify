<div>
    <h1>Create a new Application</h1>
    <div class="pb-4">Deploy any public Git repositories.</div>
    <form class="flex flex-col gap-2" wire:submit='load_branch'>
        <div class="flex flex-col gap-2">
            <div class="flex flex-col">
                <div class="flex items-end gap-2">
                    <x-forms.input required id="repository_url" label="Repository URL" helper="{!! __('repository.url') !!}" />
                    <x-forms.button type="submit">
                        Check repository
                    </x-forms.button>
                </div>
                @if (!$branch_found)
                    <div class="px-2 pt-4">
                        <div class="flex gap-1">
                            <div>Public:</div>
                            <div class='text-helper'>https://..</div>
                        </div>
                        <div class="flex gap-1">
                            <div>Private:</div>
                            <div class='text-helper'>git@..</div>
                        </div>
                        <div class="flex gap-1">
                            <div>Preselect branch (eg: main):</div>
                            <div class='text-helper'>https://github.com/coollabsio/coolify-examples/tree/main</div>
                        </div>
                        <div>
                            For example application deployments, checkout <a class="text-white underline"
                                href="https://github.com/coollabsio/coolify-examples/" target="_blank">Coolify
                                Examples</a>.
                        </div>
                @endif
                @if ($branch_found)
                    @if ($rate_limit_remaining && $rate_limit_reset)
                        <div class="flex gap-2 py-2">
                            <div>Rate Limit</div>
                            <x-helper
                                helper="Rate limit remaining: {{ $rate_limit_remaining }}<br>Rate limit reset at: {{ $rate_limit_reset }} UTC" />
                        </div>
                    @endif
                    <div class="flex flex-col gap-2 pb-6">
                        <div class="flex gap-2">
                            @if ($git_source === 'other')
                                <x-forms.input id="git_branch" label="Branch"
                                    helper="You can select other branches after configuration is done." />
                            @else
                                <x-forms.input disabled id="git_branch" label="Branch"
                                    helper="You can select other branches after configuration is done." />
                            @endif
                            <x-forms.select wire:model.live="build_pack" label="Build Pack" required>
                                <option value="nixpacks">Nixpacks</option>
                                <option value="static">Static</option>
                                <option value="dockerfile">Dockerfile</option>
                                <option value="dockercompose">Docker Compose</option>
                            </x-forms.select>
                            @if ($is_static)
                                <x-forms.input id="publish_directory" label="Publish Directory"
                                    helper="If there is a build process involved (like Svelte, React, Next, etc..), please specify the output directory for the build assets." />
                            @endif
                        </div>
                        @if ($show_is_static)
                            <x-forms.input type="number" id="port" label="Port" :readonly="$is_static || $build_pack === 'static'"
                                helper="The port your application listens on." />
                            <div class="w-52">
                                <x-forms.checkbox instantSave id="is_static" label="Is it a static site?"
                                    helper="If your application is a static site or the final build assets should be served as a static site, enable this." />
                            </div>
                        @endif
                    </div>
                    <x-forms.button wire:click.prevent='submit'>
                        Continue
                    </x-forms.button>
                @endif
            </div>
        </div>
    </form>
</div>
