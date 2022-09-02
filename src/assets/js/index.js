const all = require.context('./', true, /.js$/);
all.keys().forEach(all);
