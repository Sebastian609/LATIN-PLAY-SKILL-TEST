let selectedId = null;
let treeData = null; // Store tree data globally

$(document).ready(function () {

  const load = () => {
    $.ajax({
      url: "api/node.php",
      type: "GET",
      dataType: "json",
      contentType: "application/json",
      success: function (response) {
        if (response.data) {
          treeData = response.data; // Store tree data globally
          const treeHtml = buildTree(response.data);
          $("#familyTree").html(treeHtml);
          populateParentSelect(); // Update parent select when data loads
        }
      },
      error: function (xhr, status, error) {
        console.error("Error al cargar el árbol:", error);
      },
    });
  };

  function populateParentSelect() {
    if (!treeData) return;

    const parentSelect = $("#parentSelect");
    parentSelect.empty();
    parentSelect.append('<option value="">Seleccionar nuevo padre</option>');

    // Flatten tree to get all nodes
    const allNodes = [];
    function collectNodes(node) {
      allNodes.push({ id: node.id, name: node.name });
      if (node.children) {
        node.children.forEach(collectNodes);
      }
    }
    collectNodes(treeData);

    // Add all nodes except the currently selected one
    allNodes.forEach(node => {
      if (node.id !== selectedId) {
        parentSelect.append(`<option value="${node.id}">${node.name}</option>`);
      }
    });
  }

  function changeParent() {
    const newParentId = $("#parentSelect").val();
    if (!newParentId || !selectedId) {
      alert("Por favor, selecciona un nuevo padre.");
      return;
    }

    $.ajax({
      url: "api/node.php",
      type: "PUT",
      dataType: "json",
      data: JSON.stringify({ id: selectedId, fatherId: newParentId }),
      contentType: "application/json",
      success: function (response) {
        load(); // Reload tree
        $("#modal").addClass("hidden");
      },
      error: function (xhr, status, error) {
        load(); // Reload tree
        $("#modal").addClass("hidden");
      },
    });
  }

  function buildTree(node) {
    let html = `
                    <ul class="ml-4 w-full border-l border-gray-300 pl-4">
                        <li class="my-8" id="${node.id}">
                            <span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded cursor-pointer hover:bg-blue-200">
                                ${node.name}
                            </span>
                `;

    if (node.children && node.children.length > 0) {
      for (const child of node.children) {
        html += buildTree(child);
      }
    }

    html += `</li></ul>`;
    return html;
  }

  // Evento delegado para agregar un nuevo miembro
  $("#familyTree").on("click", "span", function (e) {
    let id = $(this).parent().attr("id");
    $("#modal").removeClass("hidden");
    selectedId = id;
    populateParentSelect(); // Update parent select when node is selected
  });

  $("#closeModal").on("click", function () {
    $("#modal").addClass("hidden");
  });

  $("#addMemberForm").on("submit", function (e) {
    e.preventDefault();
    e.stopPropagation();
    let name = $("#name").val();
    let father = selectedId;

    if (!name || !father) {
      alert("Por favor, completa todos los campos.");
      return;
    }

    $.ajax({
      url: "api/node.php",
      type: "POST",
      dataType: "json",
      data: JSON.stringify({ name: name, father: father }),
      contentType: "application/json",
      success: function (response) {
        load();
        $("#modal").addClass("hidden");
      },
      error: function (xhr, status, error) {
        load();
        $("#modal").addClass("hidden");
      },
    });
  });

  $("#delete").on("click", function (e) {
    e.preventDefault();
    e.stopPropagation();
    let id = selectedId;
    if (!id) {
      alert("Por favor, selecciona un miembro.");
      return;
    }
    $.ajax({
      url: "api/node.php",
      type: "DELETE",
      dataType: "json",
      data: JSON.stringify({ id: id }),
      contentType: "application/json",
      success: function (response) {
        load();
        $("#modal").addClass("hidden");
      },
      error: function (xhr, status, error) {
        load();
        $("#modal").addClass("hidden");
      },
    });
  });

  $("#changeParentBtn").on("click", function (e) {
    e.preventDefault();
    changeParent();
  });

  load();
});
